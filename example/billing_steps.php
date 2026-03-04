<?php 

$parent_dir = dirname(__DIR__, 1);

require $parent_dir . '/vendor/autoload.php';

use Rafael\Omiephpsdk\BillingSteps\OmieBillingStepService;

$service = new OmieBillingStepService();

$billingSteps = $service->listBillingSteps([
    'pagina' => 1,
    'registros_por_pagina' => 20,
]);

var_dump($billingSteps);
