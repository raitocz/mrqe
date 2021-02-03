<?php

declare(strict_types=1);

use Carvago\Mrqe\Kernel;

require_once('vendor/autoload.php');

$application = new Kernel();
$application->run();