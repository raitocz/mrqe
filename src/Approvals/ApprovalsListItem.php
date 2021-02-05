<?php

declare(strict_types=1);

namespace Carvago\Mrqe\Approvals;

class ApprovalsListItem
{

    public function __construct(
        private string $approvedBy
    )
    {
    }

    public function getApprovedBy(): string
    {
        return $this->approvedBy;
    }
}