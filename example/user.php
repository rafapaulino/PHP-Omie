<?php 

$parent_dir = dirname(__DIR__, 1);

require $parent_dir . '/vendor/autoload.php';

use Rafapaulino\Omiephpsdk\Users\OmieUserService;

$service = new OmieUserService();

$users = $service->listUsers([
    'pagina' => 1,
    'registros_por_pagina' => 50,
]);

var_dump($users);



