<?php 

$parent_dir = dirname(__DIR__, 1);

require $parent_dir . '/vendor/autoload.php';

use Rafael\Omiephpsdk\Config\ConfigSingleton;

$config = ConfigSingleton::getInstance()->getConfig();

//print_r($config);


use Rafael\Omiephpsdk\Clients\OmieClientService;

$service = new OmieClientService();
//$response = $service->listClients();

//$response = $service->getClient(11118162834);

$response = $service->createClient(array(
    "codigo_cliente_integracao" => "CodigoInterno0002",
    "email" => "segundo@ccliente.com.br",
    "razao_social" => "Segundo cliente LTDA",
    "nome_fantasia" => "Segundo cliente",
    "cnpj_cpf" => "81380763053"
));

var_dump($response);