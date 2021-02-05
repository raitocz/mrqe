<?

use Carvago\Mrqe\MergeRequests\MergeRequestsList;

?>
<? /** @var array<MergeRequestsList> $mergeRequestByUsers */ ?>
<? foreach ($mergeRequestByUsers as $username => $mrs): ?>

    <? if(!$mrs->isListMine()): ?>
    <h2><?= $username ?> <span class="badge bg-secondary"><?= count($mrs->getItems()) ?></span></h2>
    <? endif; ?>

    <ul class="list-group mb-5">
        <? if (count($mrs->getItems()) === 0): ?>
            <span class="text-muted">Every open merge request by <i><?= $username ?></i> is approved by you or there are no open requests <i
                    class="fa fa-fw fa-check"></i></span>
        <? endif; ?>

        <? foreach ($mrs->getItems() as $mergeRequest): ?>

            <li class="list-group-item<? if (!$mergeRequest->isPipelineStatusSuccess() || $mergeRequest->isWip(
                )): ?> text-muted bg-light<? endif; ?>">
                <div class="mb-2 d-flex justify-content-between">
                    <div>
                        <? if ($mergeRequest->isWip()): ?>
                            <i class="fa fa-fw fa-tools text-warning" title="WIP/Draft"></i>
                        <? else: ?>
                            <i class="fa fa-fw fa-check text-success" title="Ready to CR"></i>
                        <? endif; ?>

                        <a href="<?= $mergeRequest->getWebUrl() ?>"
                           <? if (!$mergeRequest->isPipelineStatusSuccess() || $mergeRequest->isWip(
                           )): ?>class="text-muted"<? endif; ?>>
                            <strong><?= $mergeRequest->getTitle() ?></strong>
                        </a>
                    </div>

                    <div>
                        <span class="text-muted">#<?= $mergeRequest->getId() ?></span>
                    </div>
                </div>

                <div class="d-flex justify-content-between small">
                    <div>
                        <strong>Date created:</strong><br>
                        <?= $mergeRequest->getCreatedAt()
                            ->format('d.m.Y H:i:s') ?>
                    </div>

                    <div>
                        <strong>Other approvals:</strong><br>
                        <span <? if ($mergeRequest->getOtherApprovals() > 0): ?>class="text-danger"<? endif; ?>>
                            <?= $mergeRequest->getOtherApprovals() ?>
                        </span>
                    </div>

                    <div>
                        <strong>Comments:</strong><br>

                        <span class="text-success">
                            <i class="fa fa-fw fa-check"></i>
                            <?= $mergeRequest->getNotesResolvedCount() ?>
                        </span>
                        <span class="text-danger">
                            <i class="fa fa-fw fa-times"></i>
                            <?= $mergeRequest->getNotesCount() - $mergeRequest->getNotesResolvedCount() ?>
                        </span>
                    </div>

                    <div>
                        <strong>Needs rebase:</strong><br>
                        <?= $mergeRequest->isHasConflicts()
                            ? "No"
                            : "Yes" ?>
                        <i class="fa fa-fw fa-code-branch"
                           title="Target branch: <?= $mergeRequest->getTargetBranch() ?>"></i>
                    </div>

                    <div>
                        <strong>Pipeline status:</strong><br>
                        <? if ($mergeRequest->isPipelineStatusSuccess()): ?>
                            <span class="badge bg-success"><i class="fa fa-fw fa-check"></i> Success</span>
                        <? elseif ($mergeRequest->isPipelineStatusPending()): ?>
                            <span class="badge bg-warning"><i class="fa fa-fw fa-clock"></i> Pending</span>
                        <? elseif ($mergeRequest->isPipelineStatusFailed()): ?>
                            <span class="badge bg-danger"><i class="fa fa-fw fa-times"></i> Failed</span>
                        <? endif; ?>
                    </div>
                </div>
            </li>

        <? endforeach; ?>
    </ul>

<? endforeach; ?>
