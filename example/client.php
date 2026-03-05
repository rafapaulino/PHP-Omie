<?php 

$parent_dir = dirname(__DIR__, 1);

require $parent_dir . '/vendor/autoload.php';

use Rafapaulino\Omiephpsdk\Clients\OmieClientService;

$service = new OmieClientService();

// Listar clientes
$clients = $service->listClients();
var_dump($clients);

// Pegar os dados de um cliente
$client = $service->getClient(11118162834);
var_dump($client);

// Adicionar cliente
$create_client = $service->createClient(array(
    "codigo_cliente_integracao" => "CodigoInterno0002",
    "email" => "segundo@ccliente.com.br",
    "razao_social" => "Segundo cliente LTDA",
    "nome_fantasia" => "Segundo cliente",
    "cnpj_cpf" => "81380763053"
));
var_dump($create_client);

// Atualizar cliente
$update_client = $service->updateClient("CodigoInterno0002", array(
    "razao_social" => "Segundo Cliente Ninja Ltda Me",
));
var_dump($update_client);

// Excluir cliente
$delete_client = $service->deleteClient(11119174794);
var_dump($delete_client);

