<?php

declare(strict_types=1);

namespace Carvago\Mrqe\MergeRequest;

use Carvago\Mrqe\Config\Config;
use Carvago\Mrqe\Config\JsonConfigRepository;
use Carvago\Mrqe\MergeRequest\Request\RequestService;
use League\Plates\Engine;

final class MergeRequestsController
{
    private Config $config;

    public function __construct(
        private Engine $template,
        private RequestService $requestService,
        JsonConfigRepository $configRepository
    ) {
        $this->config = $configRepository->getConfig();
    }

    public function __invoke(): string
    {
        $usersMergeRequests = $this->requestService->getRequestsList();
        return $this->template->render(
            'list',
            [
                'mergeRequestByUsers' => $usersMergeRequests,
                'refreshIntervalSeconds' => $this->config->getRefreshIntervalSeconds()
            ]
        );
    }
}