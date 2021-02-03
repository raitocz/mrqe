<?php

use Carvago\Mrqe\MergeRequest\MergeRequestsList;

?>
<!DOCTYPE html>
<html lang="cs-CZ">
<head>
    <title>Active Merge Requests</title>

    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content=""/>
    <meta http-equiv="refresh" content="<?= /** @var int $refreshIntervalSeconds */ $refreshIntervalSeconds ?>">

    <link rel="icon" type="image/x-icon" href=""/>
    <link rel="icon" sizes="192x192" href=""/>
    <link rel="manifest" href=""/>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW"
            crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css">
</head>

<body class="container-lg">
<h1 class="mt-5">My active merge requests:</h1>
<hr>

<? /** @var array<MergeRequestsList> $mergeRequestByUsers */ ?>
<? foreach ($mergeRequestByUsers as $username => $mrs): ?>

    <h2><?= $username ?> <span class="badge bg-secondary"><?= count($mrs->getItems()) ?></span></h2>
    <ul class="list-group mb-5">
        <? foreach ($mrs->getItems() as $mergeRequest): ?>

            <li class="list-group-item<? if(!$mergeRequest->isPipelineSuccess() || $mergeRequest->isWip()): ?> text-muted bg-light<? endif; ?>">
                <div class="mb-2">
                    <? if ($mergeRequest->isWip()): ?>
                        <i class="fa fa-fw fa-tools text-warning" title="WIP/Draft"></i>
                    <? else: ?>
                        <i class="fa fa-fw fa-check text-success"title="Ready to CR"></i>
                    <? endif; ?>

                    <a href="<?= $mergeRequest->getWebUrl() ?>" <? if(!$mergeRequest->isPipelineSuccess() || $mergeRequest->isWip()): ?>class="text-muted"<? endif; ?>>
                        <strong><?= $mergeRequest->getTitle() ?></strong>
                    </a>
                </div>

                <div class="d-flex justify-content-between small">
                    <div>
                        <strong>Date created:</strong> <?= $mergeRequest->getCreatedAt()->format('d.m.Y H:i:s') ?>
                    </div>

                    <div>
                        <strong>Target branch:</strong>
                        <?= $mergeRequest->getTargetBranch() ?>
                    </div>

                    <div>
                        <strong>Needs rebase:</strong>
                        <?= $mergeRequest->isHasConflicts() ? "Yes" : "No" ?>
                    </div>

                    <div>
                        <strong>Pipeline status:</strong>
                        <? if ($mergeRequest->isPipelineSuccess()): ?>
                            <span class="badge bg-success"><i class="fa fa-fw fa-check"></i> Success</span>
                        <? else: ?>
                            <span class="badge bg-danger"><i class="fa fa-fw fa-times"></i> Failed</span>
                        <? endif; ?>
                    </div>
                </div>
            </li>

        <? endforeach; ?>
    </ul>

<? endforeach; ?>
</body>

</html>