<?php

declare(strict_types=1);

namespace Carvago\Mrqe\MergeRequests;

use Carvago\Mrqe\Approvals\ApprovalsFacade;
use Carvago\Mrqe\Config\Config;
use Carvago\Mrqe\Config\JsonConfigRepository;
use Carvago\Mrqe\GitLab\GitLabRequestService;
use Carvago\Mrqe\Notes\NotesFacade;
use Carvago\Mrqe\Pipelines\PipelinesFacade;
use GuzzleHttp\Psr7\Query;
use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\ResponseInterface;

class MergeRequestsListFacade
{
    private const MERGE_REQUESTS_LIST_EP = '/merge_requests';

    private const QUERY_AUTHOR_USERNAME = 'author_username';
    private const QUERY_STATE = 'state';
    private const QUERY_SCOPE = 'scope';

    private Config $config;

    public function __construct(
        private GitLabRequestService $gitLabRequestService,
        private JsonConfigRepository $configRepository,
        private ApprovalsFacade $approvalsFacade,
        private PipelinesFacade $pipelinesFacade,
        private NotesFacade $notesFacade
    ) {
        $this->config = $this->configRepository->getConfig();
    }

    public function getMergeRequestsListForUser(MergeRequestUser $user): MergeRequestsList
    {
        return $this->translateResponseToList(
            $this->fetchListForUser($user),
            $user->getUsername() === $this->config->getMyUsername()
        );
    }

    private function fetchListForUser(MergeRequestUser $user): ResponseInterface
    {
        $query = [
            self::QUERY_AUTHOR_USERNAME => $user->getUsername(),
            self::QUERY_STATE => $this->config->getState(),
            self::QUERY_SCOPE => $this->config->getScope(),
        ];

        $uri = new Uri(self::MERGE_REQUESTS_LIST_EP);

        return $this->gitLabRequestService->createAndSendResponse($uri->withQuery(Query::build($query)));
    }


    private function translateResponseToList(ResponseInterface $response, bool $listMine): MergeRequestsList
    {
        $response = json_decode($response->getBody()->getContents());
        $items = [];

        foreach ($response as $item) {
            if($this->approvalsFacade->isApprovedByMe($this->config->getMyUsername(), $item->project_id, $item->iid)){
                continue;
            }

            $items[] = new MergeRequestsListItem(
                $item->iid,
                $item->title,
                new \DateTimeImmutable($item->created_at),
                $item->author->name,
                $item->target_branch,
                $item->user_notes_count,
                $this->notesFacade->getResolvedNotes($item->project_id, $item->iid),
                $item->work_in_progress,
                $item->web_url,
                $item->has_conflicts,
                $this->pipelinesFacade->getPipelineStatus($item->project_id, $item->iid),
                $this->approvalsFacade->countOtherApprovals($item->project_id, $item->iid)
            );
        }

        return new MergeRequestsList($items, $listMine);
    }
}