<?php

declare(strict_types=1);

namespace Carvago\Mrqe\MergeRequest;

class MergeRequestsList
{
    public function __construct(
        /** @var $items array<MergeRequestsListItem> */
        private array $items
    ) {
    }

    /**
     * @return array<MergeRequestsListItem>
     */
    public function getItems(): array
    {
        return $this->items;
    }


}