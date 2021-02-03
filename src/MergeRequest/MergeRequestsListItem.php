<?php

declare(strict_types=1);

namespace Carvago\Mrqe\MergeRequest;

class MergeRequestsListItem
{
    public function __construct(
        private int $id,
        private string $title,
        private \DateTimeImmutable $createdAt,
        private string $author,
        private string $targetBranch,
        private int $notesCount,
        private bool $wip,
        private string $webUrl,
        private bool $hasConflicts,
        private bool $pipelineSuccess
    )
    {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getAuthor(): string
    {
        return $this->author;
    }

    public function getTargetBranch(): string
    {
        return $this->targetBranch;
    }

    public function getNotesCount(): int
    {
        return $this->notesCount;
    }

    public function isWip(): bool
    {
        return $this->wip;
    }

    public function getWebUrl(): string
    {
        return $this->webUrl;
    }

    public function isHasConflicts(): bool
    {
        return $this->hasConflicts;
    }

    public function isPipelineSuccess(): bool
    {
        return $this->pipelineSuccess;
    }
}