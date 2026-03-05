<?php 

$parent_dir = dirname(__DIR__, 1);

require $parent_dir . '/vendor/autoload.php';

use Rafapaulino\Omiephpsdk\Products\OmieProductService;

$service = new OmieProductService();

$products = $service->listProducts();

var_dump($products);

