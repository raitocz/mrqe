<?php

declare(strict_types=1);

namespace Carvago\Mrqe\MergeRequests;

class MergeRequestsList
{
    public function __construct(
        /** @var $items array<MergeRequestsListItem> */
        private array $items,
        private bool $listMine,
    ) {
    }

    /**
     * @return array<MergeRequestsListItem>
     */
    public function getItems(): array
    {
        return $this->items;
    }

    public function isListMine(): bool
    {
        return $this->listMine;
    }
}