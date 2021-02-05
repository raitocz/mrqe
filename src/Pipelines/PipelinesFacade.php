<?php

declare(strict_types=1);

namespace Carvago\Mrqe\Pipelines;

use Carvago\Mrqe\GitLab\GitLabRequestService;
use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\ResponseInterface;

class PipelinesFacade
{
    private const PIPELINES_EP = '/projects/%s/merge_requests/%s/pipelines';

    public function __construct(private GitLabRequestService $gitLabRequestService)
    {
    }

    public function getPipelineStatus(int $projectId, int $iid): string
    {
        $pipelines = json_decode(
            $this->fetchPipelinesForMergeRequest($projectId, $iid)
                ->getBody()
                ->getContents()
        );
        $latestPipe = reset($pipelines);

        return (string) $latestPipe->status;
    }

    private function fetchPipelinesForMergeRequest(int $projectId, int $iid): ResponseInterface
    {
        $uri = new Uri(sprintf(self::PIPELINES_EP, $projectId, $iid));
        return $this->gitLabRequestService->createAndSendResponse($uri);
    }
}