<?php

declare(strict_types=1);

namespace Carvago\Mrqe\Approvals;

class ApprovalsList implements \Countable
{

    /**
     * @param array<ApprovalsListItem> $items
     */
    public function __construct(private array $items)
    {
    }

    /**
     * @return ApprovalsListItem[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    public function count()
    {
        return count($this->items);
    }

}