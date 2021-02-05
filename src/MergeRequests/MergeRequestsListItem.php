<?php

declare(strict_types=1);

namespace Carvago\Mrqe\MergeRequests;

use Carvago\Mrqe\Pipelines\PipelineStatus;

class MergeRequestsListItem
{
    public function __construct(
        private int $id,
        private string $title,
        private \DateTimeImmutable $createdAt,
        private string $author,
        private string $targetBranch,
        private int $notesCount,
        private int $notesResolvedCount,
        private bool $wip,
        private string $webUrl,
        private bool $hasConflicts,
        private string $pipelineStatus,
        private int $otherApprovals
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

    public function getNotesResolvedCount(): int
    {
        return $this->notesResolvedCount;
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

    public function getPipelineStatus(): string
    {
        return $this->pipelineStatus;
    }

    public function isPipelineStatusSuccess(): bool{
        return $this->pipelineStatus === PipelineStatus::SUCCESS;
    }

    public function isPipelineStatusPending(): bool{
        return $this->pipelineStatus === PipelineStatus::PENDING;
    }

    public function isPipelineStatusFailed(): bool{
        return $this->pipelineStatus === PipelineStatus::FAILED;
    }

    public function getOtherApprovals(): int
    {
        return $this->otherApprovals;
    }

}