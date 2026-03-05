<?php 

$parent_dir = dirname(__DIR__, 1);

require $parent_dir . '/vendor/autoload.php';

use Rafapaulino\Omiephpsdk\Sellers\OmieSellerService;

$service = new OmieSellerService();

/* gero um nome aleatorio apenas para o cadastro */
$nomes = [
    "JoÃ£o", "Maria", "JosÃ©", "Ana", "Carlos",
    "Paulo", "Mariana", "Lucas", "Fernanda", "Ricardo",
    "Juliana", "Gabriel", "PatrÃ­cia", "Rafael", "Camila"
];

$sobrenomes = [
    "Silva", "Santos", "Oliveira", "Souza", "Pereira",
    "Costa", "Rodrigues", "Almeida", "Nascimento", "Lima",
    "AraÃºjo", "Fernandes", "Carvalho", "Gomes", "Martins"
];

$nomeAleatorio = $nomes[array_rand($nomes)];
$sobrenomeAleatorio = $sobrenomes[array_rand($sobrenomes)];

$nomeCompleto = $nomeAleatorio . " " . $sobrenomeAleatorio;

$seller = $service->createSeller(array(
    'codInt' => time(),
    'nome' => $nomeCompleto,
    'inativo' => 'N',
    'email' => 'fulano.' . time() . '@minhaempresa.com.br',
    'fatura_pedido' => 'S',
    'visualiza_pedido' => 'N',
    'comissao' => rand(5,30)
));
var_dump($seller);

//lista todos os vendedores
$sellers = $service->listSellers([
    'pagina' => 1,
    'registros_por_pagina' => 100,
    'apenas_importado_api' => 'N',
]);

var_dump($sellers);


