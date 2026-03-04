<?php 

$parent_dir = dirname(__DIR__, 1);

require $parent_dir . '/vendor/autoload.php';

use Rafael\Omiephpsdk\PaymentMethods\OmiePaymentMethodService;

$service = new OmiePaymentMethodService();

$paymentMethods = $service->listPaymentMethods([
    'pagina' => 1,
    'registros_por_pagina' => 50,
]);

var_dump($paymentMethods);

