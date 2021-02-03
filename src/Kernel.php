<?php

declare(strict_types=1);

namespace Carvago\Mrqe;

use Carvago\Mrqe\Config\JsonConfigRepository;
use Carvago\Mrqe\Config\JsonFileReader;
use Carvago\Mrqe\MergeRequest\MergeRequestsController;
use Carvago\Mrqe\MergeRequest\Request\RequestService;
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
        $handler = new Run;
        $handler->pushHandler(new PrettyPageHandler);
        $handler->register();
    }

    private function loadContainer(): void
    {
        $container = new Container();

        $container->add(Engine::class)->addArgument(self::DIR_ROOT . '/src/Template');
        $container->add(JsonFileReader::class);
        $container->add(JsonConfigRepository::class)->addArgument(JsonFileReader::class);
        $container->add(Client::class);
        $container->add(RequestService::class)->addArguments([
            $container->get(JsonConfigRepository::class),
            $container->get(Client::class),
        ]);

        $this->container = $container;
    }

    private function executeDefaultController(): void
    {
        $controller = new MergeRequestsController(
            $this->container->get(Engine::class),
            $this->container->get(RequestService::class),
            $this->container->get(JsonConfigRepository::class)
        );
        echo $controller();
    }
}