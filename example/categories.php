<?php 

$parent_dir = dirname(__DIR__, 1);

require $parent_dir . '/vendor/autoload.php';

use Rafapaulino\Omiephpsdk\Categories\OmieCategoryService;

$service = new OmieCategoryService();

$categories = $service->listCategories([
    'pagina' => 1,
    'registros_por_pagina' => 50,
]);

var_dump($categories);



