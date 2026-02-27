<?php 

$parent_dir = dirname(__DIR__, 1);

require $parent_dir . '/vendor/autoload.php';

use Rafael\Omiephpsdk\Sellers\OmieSellerService;

$service = new OmieSellerService();

$sellers = $service->listSellers(array(
    'pagina' => 1,
    'registros_por_pagina' => 50
));

print_r($sellers);