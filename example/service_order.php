<?php

$parent_dir = dirname(__DIR__, 1);

require $parent_dir . '/vendor/autoload.php';

use Rafapaulino\Omiephpsdk\ServiceOrders\OmieServiceOrderService;

$service = new OmieServiceOrderService();

$os = $service->createServiceOrder(array(
    'Cabecalho' => array(
        'cCodIntOS' => (string) time(),
        'cCodParc' => '999',
        'cEtapa' => '10',
        'dDtPrevisao' => date('d/m/Y'),
        'nCodCli' => 3203383223,
        'nQtdeParc' => 1,
    ),
    'Observacoes' => array(
        'cObsOS' => 'teste',
    ),
    'InformacoesAdicionais' => array(
        'cCidPrestServ' => 'SAO PAULO (SP)',
        'cCodCateg' => '1.01.02',
        'cDadosAdicNF' => 'OS incluida via API em: ' . date('d/m/Y H:i:s'),
        'nCodCC' => 3203383209,
    ),
    'ServicosPrestados' => array(
        array(
            'nCodServico' => 3203530838,
            'nQtde' => 1,
            'nValUnit' => 0,
        ),
    ),
));

var_dump($os);

