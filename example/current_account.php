<?php 

$parent_dir = dirname(__DIR__, 1);

require $parent_dir . '/vendor/autoload.php';

use Rafapaulino\Omiephpsdk\CurrentAccount\OmieCurrentAccountService;

$service = new OmieCurrentAccountService();

$accounts = $service->listCurrentAccounts([
    'pagina' => 1,
    'registros_por_pagina' => 100,
]);

var_dump($accounts);


