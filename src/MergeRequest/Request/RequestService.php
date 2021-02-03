<?php

declare(strict_types=1);

namespace Carvago\Mrqe\MergeRequest\Request;

use Carvago\Mrqe\Config\Config;
use Carvago\Mrqe\Config\JsonConfigRepository;
use Carvago\Mrqe\MergeRequest\MergeRequestsList;
use Carvago\Mrqe\MergeRequest\MergeRequestsListItem;
use Carvago\Mrqe\MergeRequest\MergeRequestUser;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Query;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\ResponseInterface;

class RequestService
{
    private const BASE_URI = 'https://gitlab.carvago.com/api/v4';
    private const MERGE_REQUEST_EP_METHOD = 'GET';
    private const MERGE_REQUEST_EP = self::BASE_URI . '/merge_requests';

    private const PIPELINES_EP_METHOD = 'GET';
    private const PIPELINES_EP = self::BASE_URI . '/projects/%s/merge_requests/%s/pipelines';
    private const PIPELINE_SUCCESS = 'success';

    private const HEADER_AUTH = 'PRIVATE-TOKEN';

    private const MR_QUERY_AUTHOR_USERNAME = 'author_username';
    private const MR_QUERY_STATE = 'state';
    private const MR_QUERY_SCOPE = 'scope';

    private const APPROVALS_EP_METHOD = 'GET';
    private const APPROVALS_EP = self::BASE_URI . '/projects/%s/merge_requests/%s/approvals';

    private Config $config;

    public function __construct(private JsonConfigRepository $configRepository, private ClientInterface $client)
    {
        $this->config = $this->configRepository->getConfig();
    }

    public function getRequestsList(): array
    {
        $usersMergeRequests = [];

        foreach ($this->config->getFollowedUsers() as $followedUser)
        {
            $user = new MergeRequestUser($followedUser);
            $usersMergeRequests[$user->getUsername()] = $this->translateResponseToList($this->fetchListForUser($user));
        }

        return $usersMergeRequests;
    }

    private function fetchListForUser(MergeRequestUser $user): ResponseInterface
    {
        $headers = [
            self::HEADER_AUTH => $this->config->getPersonalAccessToken(),
        ];

        $query = [
            self::MR_QUERY_AUTHOR_USERNAME => $user->getUsername(),
            self::MR_QUERY_STATE => $this->config->getState(),
            self::MR_QUERY_SCOPE => $this->config->getScope(),
        ];

        $request = $this->createMergeRequestListRequest($headers, $query);
        return $this->client->send($request);
    }


    private function translateResponseToList(ResponseInterface $response): MergeRequestsList
    {
        $response = json_decode($response->getBody()->getContents());
        $items = [];

        foreach ($response as $item) {
            if($this->isApprovedByMe($this->config->getMyUsername(), $item->project_id, $item->iid)){
                continue;
            }

            $items[] = new MergeRequestsListItem(
                $item->iid,
                $item->title,
                new \DateTimeImmutable($item->created_at),
                $item->author->name,
                $item->target_branch,
                $item->user_notes_count,
                $item->work_in_progress,
                $item->web_url,
                $item->has_conflicts,
                $this->isPipelineSucceedForMr($item->project_id, $item->iid)
            );
        }

        return new MergeRequestsList($items);
    }

    private function createMergeRequestListRequest(array $headers, array $query): Request
    {
        $uri = new Uri(self::MERGE_REQUEST_EP);

        return new Request(self::MERGE_REQUEST_EP_METHOD, $uri->withQuery(Query::build($query)), $headers);
    }

    private function fetchPipelinesForMergeRequest(int $projectId, int $iid): ResponseInterface
    {
        $headers = [
            self::HEADER_AUTH => $this->config->getPersonalAccessToken(),
        ];

        $request = $this->createPipelineStatusRequest($headers, $projectId, $iid);
        return $this->client->send($request);
    }

    private function isPipelineSucceedForMr(int $projectId, int $iid): bool
    {
        $pipelines = json_decode($this->fetchPipelinesForMergeRequest($projectId, $iid)->getBody()->getContents());
        $latestPipe = reset($pipelines);

        return $latestPipe->status === self::PIPELINE_SUCCESS;
    }

    private function createPipelineStatusRequest(array $headers, int $projectId, int $iid): Request
    {
        $uri = new Uri(sprintf(self::PIPELINES_EP, $projectId, $iid));

        return new Request(self::PIPELINES_EP_METHOD, $uri, $headers);
    }

    private function isApprovedByMe(string $username, int $projectId, int $iid): bool
    {
        $approvals = json_decode($this->fetchApprovalsForMergeRequest($projectId, $iid)->getBody()->getContents());

        foreach ($approvals->approved_by as $item) {
            if($item->user->username === $username){
                return true;
            }
        }

        return false;
    }

    private function fetchApprovalsForMergeRequest(int $projectId, int $iid): ResponseInterface
    {
        $headers = [
            self::HEADER_AUTH => $this->config->getPersonalAccessToken(),
        ];

        $request = $this->createApprovalsStatusRequest($headers, $projectId, $iid);
        return $this->client->send($request);
    }

    private function createApprovalsStatusRequest(array $headers, int $projectId, int $iid): Request
    {
        $uri = new Uri(sprintf(self::APPROVALS_EP, $projectId, $iid));

        return new Request(self::APPROVALS_EP_METHOD, $uri, $headers);
    }
}