<?php

declare(strict_types=1);

namespace Carvago\Mrqe\Config;

class JsonConfigRepository
{
    private ?Config $config = null;

    private ?string $json;

    private JsonFileReader $jsonReader;

    public function __construct(JsonFileReader $jsonReader)
    {
        $this->jsonReader = $jsonReader;
    }

    public function getConfig(): Config
    {
        if ($this->config !== null) {
            return $this->config;
        }

        $this->json = $this->jsonReader->getContent();

        return $this->config = $this->hydrateConfigWithJsonContent($this->json);
    }

    private function hydrateConfigWithJsonContent(string $json): Config
    {
        $obj = json_decode($json);

        return new Config(
            $obj->myUsername, $obj->personalAccessToken, $obj->followedUsers, $obj->refreshIntervalSeconds
        );
    }

}