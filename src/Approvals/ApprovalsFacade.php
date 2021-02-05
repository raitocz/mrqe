<?php

declare(strict_types=1);

namespace Carvago\Mrqe\Approvals;

use Carvago\Mrqe\GitLab\GitLabRequestService;
use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\ResponseInterface;

class ApprovalsFacade
{
    private const APPROVALS_EP = '/projects/%s/merge_requests/%s/approvals';

    private array $approvals;

    public function __construct(private GitLabRequestService $gitLabRequestService)
    {
    }

    public function isApprovedByMe(string $username, int $projectId, int $iid): bool
    {
        $approvals = $this->getApprovalsForId($projectId, $iid);

        foreach ($approvals->getItems() as $approval) {
            if($approval->getApprovedBy() === $username){
                return true;
            }
        }

        return false;
    }

    public function countOtherApprovals(int $projectId, int $iid): int
    {
        return $this->getApprovalsForId($projectId, $iid)->count();
    }


    private function getApprovalsForId(int $projectId, int $iid): ApprovalsList
    {
        return $this->approvals[$projectId][$iid] ??
            $this->translateResponseToList($this->fetchApprovalsForMergeRequest($projectId, $iid));
    }

    private function translateResponseToList(ResponseInterface $response): ApprovalsList
    {
        $response = json_decode($response->getBody()->getContents());
        $approvesList = [];

        foreach($response->approved_by as $approves)
        {
            $approvesList[] = new ApprovalsListItem($approves->user->username);
        }

        return new ApprovalsList($approvesList);
    }
    private function fetchApprovalsForMergeRequest(int $projectId, int $iid): ResponseInterface
    {
        $uri = new Uri(sprintf(self::APPROVALS_EP, $projectId, $iid));
        return $this->gitLabRequestService->createAndSendResponse($uri);
    }
}