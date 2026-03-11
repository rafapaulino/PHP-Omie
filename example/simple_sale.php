<?php

$parent_dir = dirname(__DIR__, 1);

require $parent_dir . '/vendor/autoload.php';

use Rafapaulino\Omiephpsdk\SimpleSale\OmieSimpleSaleService;

$service = new OmieSimpleSaleService();

//adiciona uma venda
$sale = $service->addOrder(array(
    'codigo_pedido_integracao' => time(),
    'codigo_cliente' => 3203383223, //Roger - Papelaria e Livraria RÃ¡pida
    'codigo_cenario_impostos' => 0,
    'codigo_categoria' => '1.01.01',
    'codigo_conta_corrente' => 3203383209, //bradesco
    'itens' => [
        [
            'codigo_produto' => 3203383366, //Game Wii Party - Wii
            'quantidade' => 1,
            'valor_unitario' => 0,
            'cfop' => "1.102",
            'codigo_cenario_impostos_item' => 0
        ]
    ]
));

var_dump($sale);

//adiciona observacao no pedido faturado
$saleObs = $service->addObs([
    'codigo_pedido' => $sale['codigo_pedido'],
    'obs_venda' => 'Comentario adicionado em: ' . date("d/m/Y H:i:s"),
]);

var_dump($saleObs);

//listagem de pedidos
$orders = $service->listOrders([
    'pagina' => 1,
    'registros_por_pagina' => 100,
    'apenas_importado_api' => 'N',
]);

var_dump($orders);
