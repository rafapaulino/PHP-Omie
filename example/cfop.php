<?php 

$parent_dir = dirname(__DIR__, 1);

require $parent_dir . '/vendor/autoload.php';

use Rafael\Omiephpsdk\CFOP\OmieCfopService;

$service = new OmieCfopService();

$cfop = $service->listCfop();

var_dump($cfop);