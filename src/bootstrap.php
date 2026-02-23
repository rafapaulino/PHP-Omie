<?php 

$parent_dir = dirname(__DIR__, 1);

require $parent_dir . '/vendor/autoload.php';

use Rafael\Omiephpsdk\Config\ConfigSingleton;

$config = ConfigSingleton::getInstance()->getConfig();

//print_r($config);


use Rafael\Omiephpsdk\Clients\OmieClientService;

$service = new OmieClientService();
$response = $service->listClients();

//var_dump($response);