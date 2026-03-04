<?php

declare(strict_types=1);

use GuzzleHttp\ClientInterface;
use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Rafael\Omiephpsdk\SimpleSale\OmieSimpleSaleService;

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

it('throws when Omie credentials are missing for simple sale service', function (): void {
    $_ENV['APP_KEY'] = '';
    $_ENV['APP_SECRET'] = '';
    putenv('APP_KEY=');
    putenv('APP_SECRET=');

    $httpClient = $this->createMock(ClientInterface::class);
    $httpClient->expects($this->never())->method('request');

    $service = new OmieSimpleSaleService($httpClient);

    $call = fn (): array => $service->addOrder([]);

    expect($call)->toThrow(RuntimeException::class, 'Missing Omie credentials in configuration.');
});

it('throws when required root field is missing in addOrder payload', function (): void {
    $_ENV['APP_KEY'] = 'test-app-key';
    $_ENV['APP_SECRET'] = 'test-app-secret';
    putenv('APP_KEY=test-app-key');
    putenv('APP_SECRET=test-app-secret');

    $httpClient = $this->createMock(ClientInterface::class);
    $httpClient->expects($this->never())->method('request');

    $service = new OmieSimpleSaleService($httpClient);

    $payloadWithoutContaCorrente = [
        'codigo_pedido_integracao' => '123456',
        'codigo_cliente' => 45621546,
        'codigo_cenario_impostos' => 65468465,
        'codigo_categoria' => '1.01.01',
        'itens' => [[
            'codigo_produto' => 0,
            'quantidade' => 1,
            'valor_unitario' => 1,
            'cfop' => '1.102',
            'codigo_cenario_impostos_item' => 0,
        ]],
    ];

    $call = fn (): array => $service->addOrder($payloadWithoutContaCorrente);

    expect($call)->toThrow(
        InvalidArgumentException::class,
        'Missing required field for addOrder: codigo_conta_corrente.'
    );
});

it('throws when required item field is missing in addOrder payload', function (): void {
    $_ENV['APP_KEY'] = 'test-app-key';
    $_ENV['APP_SECRET'] = 'test-app-secret';
    putenv('APP_KEY=test-app-key');
    putenv('APP_SECRET=test-app-secret');

    $httpClient = $this->createMock(ClientInterface::class);
    $httpClient->expects($this->never())->method('request');

    $service = new OmieSimpleSaleService($httpClient);

    $payloadWithoutCfop = [
        'codigo_pedido_integracao' => '123456',
        'codigo_cliente' => 45621546,
        'codigo_cenario_impostos' => 65468465,
        'codigo_categoria' => '1.01.01',
        'codigo_conta_corrente' => 65468466,
        'itens' => [[
            'codigo_produto' => 0,
            'quantidade' => 1,
            'valor_unitario' => 1,
            'codigo_cenario_impostos_item' => 0,
        ]],
    ];

    $call = fn (): array => $service->addOrder($payloadWithoutCfop);

    expect($call)->toThrow(
        InvalidArgumentException::class,
        'Missing required item field for addOrder: itens[0].cfop.'
    );
});
