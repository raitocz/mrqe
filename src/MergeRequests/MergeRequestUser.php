<?php

declare(strict_types=1);

namespace Carvago\Mrqe\MergeRequests;

class MergeRequestUser
{
    public function __construct(
        private string $username
    ) {
    }

    public function getUsername(): string
    {
        return $this->username;
    }
}