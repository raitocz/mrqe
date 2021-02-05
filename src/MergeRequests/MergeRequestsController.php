<?php

declare(strict_types=1);

namespace Carvago\Mrqe\MergeRequests;

use Carvago\Mrqe\Config\Config;
use Carvago\Mrqe\Config\JsonConfigRepository;
use Carvago\Mrqe\RequestsOverview\RequestsOverviewListService;
use League\Plates\Engine;

final class MergeRequestsController
{
    private Config $config;

    public function __construct(
        private Engine $template,
        private RequestsOverviewListService $requestService,
        JsonConfigRepository $configRepository
    ) {
        $this->config = $configRepository->getConfig();
    }

    public function __invoke(): string
    {
        $usersMergeRequests = $this->requestService->getRequestsList($this->config->getFollowedUsers());
        $myMergeRequests = $this->requestService->getRequestsList([$this->config->getMyUsername()]);
        return $this->template->render(
            'list',
            [
                'mergeRequestByUsers' => $usersMergeRequests,
                'myMergeRequests' => $myMergeRequests,
                'refreshIntervalSeconds' => $this->config->getRefreshIntervalSeconds()
            ]
        );
    }
}