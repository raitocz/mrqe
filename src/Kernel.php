<?php

declare(strict_types=1);

namespace Carvago\Mrqe;

use Carvago\Mrqe\Approvals\ApprovalsFacade;
use Carvago\Mrqe\Config\JsonConfigRepository;
use Carvago\Mrqe\Config\JsonFileReader;
use Carvago\Mrqe\GitLab\GitLabRequestService;
use Carvago\Mrqe\MergeRequests\MergeRequestsController;
use Carvago\Mrqe\MergeRequests\MergeRequestsListFacade;
use Carvago\Mrqe\Notes\NotesFacade;
use Carvago\Mrqe\Pipelines\PipelinesFacade;
use Carvago\Mrqe\RequestsOverview\RequestsOverviewListService;
use GuzzleHttp\Client;
use League\Container\Container;
use League\Plates\Engine;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

class Kernel
{
    public const DIR_ROOT = '/app';

    private Container $container;

    public function __construct()
    {
        $this->boot();
    }

    public function boot(): void
    {
        $this->loadErrorHandler();
        $this->loadContainer();
    }

    public function run(): void
    {
        $this->executeDefaultController();
    }

    private function loadErrorHandler(): void
    {
        $handler = new Run();
        $handler->pushHandler(new PrettyPageHandler());
        $handler->register();
    }

    private function loadContainer(): void
    {
        $container = new Container();

        $container->add(Engine::class)->addArgument(self::DIR_ROOT . '/src/Template');

        $container->add(JsonFileReader::class);
        $container->add(JsonConfigRepository::class)->addArgument(JsonFileReader::class);

        $container->add(Client::class);

        $container->add(GitLabRequestService::class)->addArguments([
            JsonConfigRepository::class,
            Client::class,
        ]);

        $container->add(ApprovalsFacade::class)->addArgument(GitLabRequestService::class);
        $container->add(PipelinesFacade::class)->addArgument(GitLabRequestService::class);
        $container->add(NotesFacade::class)->addArgument(GitLabRequestService::class);
        $container->add(MergeRequestsListFacade::class)->addArguments([
            GitLabRequestService::class,
            JsonConfigRepository::class,
            ApprovalsFacade::class,
            PipelinesFacade::class,
            NotesFacade::class
        ]);

        $container->add(RequestsOverviewListService::class)->addArguments([
            JsonConfigRepository::class,
            MergeRequestsListFacade::class
        ]);

        $this->container = $container;
    }

    private function executeDefaultController(): void
    {
        $controller = new MergeRequestsController(
            $this->container->get(Engine::class),
            $this->container->get(RequestsOverviewListService::class),
            $this->container->get(JsonConfigRepository::class)
        );
        echo $controller();
    }
}