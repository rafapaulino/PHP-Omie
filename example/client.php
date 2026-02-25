<?php 

$parent_dir = dirname(__DIR__, 1);

require $parent_dir . '/vendor/autoload.php';

use Rafael\Omiephpsdk\Clients\OmieClientService;

$service = new OmieClientService();

// Listar clientes
$clients = $service->listClients();
print_r($clients);

// Pegar os dados de um cliente
$client = $service->getClient(11118162834);
print_r($client);

// Adicionar cliente
$create_client = $service->createClient(array(
    "codigo_cliente_integracao" => "CodigoInterno0002",
    "email" => "segundo@ccliente.com.br",
    "razao_social" => "Segundo cliente LTDA",
    "nome_fantasia" => "Segundo cliente",
    "cnpj_cpf" => "81380763053"
));
print_r($create_client);

// Atualizar cliente
$update_client = $service->updateClient("CodigoInterno0002", array(
    "razao_social" => "Segundo Cliente Ninja Ltda Me",
));
print_r($update_client);

// Excluir cliente
$delete_client = $service->deleteClient(11119174794);
print_r($delete_client);