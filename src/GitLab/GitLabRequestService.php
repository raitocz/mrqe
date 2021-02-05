<?php

declare(strict_types=1);

namespace Carvago\Mrqe\GitLab;

use Carvago\Mrqe\Config\Config;
use Carvago\Mrqe\Config\JsonConfigRepository;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;

class GitLabRequestService
{
    private const METHOD_GET = 'GET';
    private const BASE_URI = 'https://gitlab.carvago.com/api/v4';
    private const HEADER_AUTH = 'PRIVATE-TOKEN';

    private Config $config;

    public function __construct(private JsonConfigRepository $configRepository, private ClientInterface $client)
    {
        $this->config = $this->configRepository->getConfig();
    }

    public function createAndSendResponse(
        UriInterface $endpoint,
        ?array $headers = [],
        ?string $method = self::METHOD_GET
    ): ResponseInterface {
        $request = $this->createRequest($endpoint, $headers, $method);
        return $this->send($request);
    }

    public function createRequest(UriInterface $endpoint, array $headers, string $method = self::METHOD_GET): RequestInterface
    {
        $headers[self::HEADER_AUTH] = $this->config->getPersonalAccessToken();

        return new Request($method, self::BASE_URI . $endpoint, $headers);
    }

    public function send(RequestInterface $request): ResponseInterface
    {
        return $this->client->send($request);
    }
}
