<?php 

$parent_dir = dirname(__DIR__, 1);

require $parent_dir . '/vendor/autoload.php';

use Rafael\Omiephpsdk\SimpleSale\OmieSimpleSaleService;

$service = new OmieSimpleSaleService();

//adiciona uma venda
$sale = $service->addOrder(array(
    'codigo_pedido_integracao' => time(),
    'codigo_cliente' => 3060886375, //Roger - Papelaria e Livraria Rápida
    'codigo_cenario_impostos' => 0,
    'codigo_categoria' => '1.01.01',
    'codigo_conta_corrente' => 3060886366, //bradesco
    'itens' => [
        [
            'codigo_produto' => 3060886531, //Game Wii Party - Wii
            'quantidade' => 1,
            'valor_unitario' => 0,
            'cfop' => '1.102',
            'codigo_cenario_impostos_item' => 0
        ]
    ]
));

var_dump($sale);

//listagem de pedidos
