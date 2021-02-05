<?

use Carvago\Mrqe\MergeRequests\MergeRequestsList;

?>
<!DOCTYPE html>
<html lang="cs-CZ">
<head>
    <title>Active Merge Requests</title>

    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content=""/>
    <meta http-equiv="refresh" content="<?= /** @var int $refreshIntervalSeconds */
    $refreshIntervalSeconds ?>">

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

<body class="container-fluid">

<div class="row">
    <div class="col col-lg-6 col-md-12 col-sm-12">
        <h1 class="mt-5">Active merge requests:</h1>
        <hr>

        <? /** @var array<MergeRequestsList> $mergeRequestByUsers */ ?>
        <?= $this->insert('table', ['mergeRequestByUsers' => $mergeRequestByUsers]) ?>
    </div>


    <div class="col col-lg-6 col-md-12 col-sm-12">
        <h1 class="mt-5">My open requests:</h1>
        <hr>

        <? /** @var array<MergeRequestsList> $myMergeRequests */ ?>
        <?= $this->insert('table', ['mergeRequestByUsers' => $myMergeRequests]) ?>
    </div>
</div>

</body>

</html>