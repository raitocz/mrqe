<?php

declare(strict_types=1);

namespace Carvago\Mrqe\Config;

class Config
{
    private const SCOPE = 'all';
    private const STATE = 'opened';

    public function __construct(
        private string $myUsername,
        private string $personalAccessToken,
        private array $followedUsers = [],
        private int $refreshIntervalSeconds = 60,
    ) {
    }

    public function getMyUsername(): string
    {
        return $this->myUsername;
    }

    public function getPersonalAccessToken(): string
    {
        return $this->personalAccessToken;
    }

    public function getFollowedUsers(): array
    {
        return $this->followedUsers;
    }

    public function getRefreshIntervalSeconds(): int
    {
        return $this->refreshIntervalSeconds;
    }

    public function getScope(): string
    {
        return self::SCOPE;
    }

    public function getState(): string
    {
        return self::STATE;
    }
}