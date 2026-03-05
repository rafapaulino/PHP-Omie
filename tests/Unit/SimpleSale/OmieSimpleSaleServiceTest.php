<?php

declare(strict_types=1);

use GuzzleHttp\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Rafapaulino\Omiephpsdk\SimpleSale\OmieSimpleSaleService;


beforeEach(function (): void {
    resetConfigSingleton();
});

afterEach(function (): void {
    resetConfigSingleton();
});

it('adds an order using AdicionarPedido payload', function (): void {
    $api = 'https://app.omie.com.br/api/v1/';
    $key = 'test-app-key';
    $secret = 'test-app-secret';

    $_ENV['OMIE_API'] = $api;
    $_ENV['APP_KEY'] = $key;
    $_ENV['APP_SECRET'] = $secret;

    putenv('OMIE_API=' . $api);
    putenv('APP_KEY=' . $key);
    putenv('APP_SECRET=' . $secret);

    $orderPayload = [
        'codigo_pedido_integracao' => '123456',
        'codigo_cliente' => 45621546,
        'codigo_cenario_impostos' => 65468465,
        'codigo_categoria' => '1.01.01',
        'codigo_conta_corrente' => 65468466,
        'itens' => [[
            'codigo_produto' => 0,
            'quantidade' => 1,
            'valor_unitario' => 1,
            'cfop' => '1.102',
            'codigo_cenario_impostos_item' => 0,
        ]],
    ];

    $responseBody = json_encode([
        'codigo_status' => '0',
        'descricao_status' => 'Pedido incluido com sucesso!',
    ], JSON_THROW_ON_ERROR);

    $stream = $this->createMock(StreamInterface::class);
    $stream->expects($this->once())
        ->method('__toString')
        ->willReturn($responseBody);

    $response = $this->createMock(ResponseInterface::class);
    $response->expects($this->once())
        ->method('getBody')
        ->willReturn($stream);

    $httpClient = $this->createMock(ClientInterface::class);
    $httpClient->expects($this->once())
        ->method('request')
        ->with(
            'POST',
            'produtos/pedidovenda/',
            [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'call' => 'AdicionarPedido',
                    'param' => [$orderPayload],
                    'app_key' => $key,
                    'app_secret' => $secret,
                ],
            ]
        )
        ->willReturn($response);

    $service = new OmieSimpleSaleService($httpClient);

    $result = $service->addOrder($orderPayload);

    expect($result)->toBeArray()
        ->and($result['codigo_status'])->toBe('0')
        ->and($result['descricao_status'])->toBe('Pedido incluido com sucesso!');
});

it('lists orders using ListarPedidos payload with merged filters', function (): void {
    $api = 'https://app.omie.com.br/api/v1/';
    $key = 'test-app-key';
    $secret = 'test-app-secret';

    $_ENV['OMIE_API'] = $api;
    $_ENV['APP_KEY'] = $key;
    $_ENV['APP_SECRET'] = $secret;

    putenv('OMIE_API=' . $api);
    putenv('APP_KEY=' . $key);
    putenv('APP_SECRET=' . $secret);

    $filters = [
        'pagina' => 2,
        'registros_por_pagina' => 50,
    ];

    $responseBody = json_encode([
        'pagina' => 2,
        'total_de_paginas' => 1,
        'pedido_venda_produto' => [
            ['numero_pedido' => 123, 'codigo_pedido_integracao' => 'ABC-123'],
        ],
    ], JSON_THROW_ON_ERROR);

    $stream = $this->createMock(StreamInterface::class);
    $stream->expects($this->once())
        ->method('__toString')
        ->willReturn($responseBody);

    $response = $this->createMock(ResponseInterface::class);
    $response->expects($this->once())
        ->method('getBody')
        ->willReturn($stream);

    $httpClient = $this->createMock(ClientInterface::class);
    $httpClient->expects($this->once())
        ->method('request')
        ->with(
            'POST',
            'produtos/pedido/',
            [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'call' => 'ListarPedidos',
                    'param' => [[
                        'pagina' => 2,
                        'registros_por_pagina' => 50,
                        'apenas_importado_api' => 'N',
                    ]],
                    'app_key' => $key,
                    'app_secret' => $secret,
                ],
            ]
        )
        ->willReturn($response);

    $service = new OmieSimpleSaleService($httpClient);

    $result = $service->listOrders($filters);

    expect($result)->toBeArray()
        ->and($result['pagina'])->toBe(2)
        ->and($result['pedido_venda_produto'])->toHaveCount(1)
        ->and($result['pedido_venda_produto'][0]['numero_pedido'])->toBe(123);
});

it('adds sale observation using AlterarPedFaturado payload', function (): void {
    $api = 'https://app.omie.com.br/api/v1/';
    $key = 'test-app-key';
    $secret = 'test-app-secret';

    $_ENV['OMIE_API'] = $api;
    $_ENV['APP_KEY'] = $key;
    $_ENV['APP_SECRET'] = $secret;

    putenv('OMIE_API=' . $api);
    putenv('APP_KEY=' . $key);
    putenv('APP_SECRET=' . $secret);

    $obsPayload = [
        'codigo_pedido' => 3061133689,
        'obs_venda' => 'ahdgasdfsgdfsg',
    ];

    $responseBody = json_encode([
        'codigo_status' => '0',
        'descricao_status' => 'Pedido alterado com sucesso!',
    ], JSON_THROW_ON_ERROR);

    $stream = $this->createMock(StreamInterface::class);
    $stream->expects($this->once())
        ->method('__toString')
        ->willReturn($responseBody);

    $response = $this->createMock(ResponseInterface::class);
    $response->expects($this->once())
        ->method('getBody')
        ->willReturn($stream);

    $httpClient = $this->createMock(ClientInterface::class);
    $httpClient->expects($this->once())
        ->method('request')
        ->with(
            'POST',
            'produtos/pedido/',
            [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'call' => 'AlterarPedFaturado',
                    'param' => [$obsPayload],
                    'app_key' => $key,
                    'app_secret' => $secret,
                ],
            ]
        )
        ->willReturn($response);

    $service = new OmieSimpleSaleService($httpClient);

    $result = $service->addObs($obsPayload);

    expect($result)->toBeArray()
        ->and($result['codigo_status'])->toBe('0')
        ->and($result['descricao_status'])->toBe('Pedido alterado com sucesso!');
});


