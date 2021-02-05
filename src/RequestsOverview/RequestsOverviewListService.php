<?php

declare(strict_types=1);

namespace Carvago\Mrqe\RequestsOverview;

use Carvago\Mrqe\Config\Config;
use Carvago\Mrqe\Config\JsonConfigRepository;
use Carvago\Mrqe\MergeRequests\MergeRequestsListFacade;
use Carvago\Mrqe\MergeRequests\MergeRequestUser;

class RequestsOverviewListService
{
    private Config $config;

    public function __construct(
        private JsonConfigRepository $configRepository,
        private MergeRequestsListFacade $listFacade
    )
    {
        $this->config = $this->configRepository->getConfig();
    }

    public function getRequestsList(array $users): array
    {
        $usersMergeRequests = [];

        foreach ($users as $followedUser)
        {
            $user = new MergeRequestUser($followedUser);
            $usersMergeRequests[$user->getUsername()] = $this->listFacade->getMergeRequestsListForUser($user);
        }

        return $usersMergeRequests;
    }

}