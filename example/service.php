<?php

$parent_dir = dirname(__DIR__, 1);

require $parent_dir . '/vendor/autoload.php';

use Rafapaulino\Omiephpsdk\Services\OmieServiceService;

$service = new OmieServiceService();

$services = $service->listServices(array(
    'nPagina' => 1,
    'nRegPorPagina' => 100
));

echo '<pre>';
print_r($services);
echo '</pre>';
