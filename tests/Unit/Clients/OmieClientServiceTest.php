<?php

declare(strict_types=1);

use GuzzleHttp\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Rafael\Omiephpsdk\Clients\OmieClientService;
use Rafael\Omiephpsdk\Config\ConfigSingleton;
//use RuntimeException;

beforeEach(function (): void {
    resetConfigSingleton();
});

afterEach(function (): void {
    resetConfigSingleton();
});

it('gets a client using ConsultarCliente payload', function (): void {
    $api = 'https://app.omie.com.br/api/v1/';
    $key = 'test-app-key';
    $secret = 'test-app-secret';

    $_ENV['OMIE_API'] = $api;
    $_ENV['APP_KEY'] = $key;
    $_ENV['APP_SECRET'] = $secret;

    putenv('OMIE_API=' . $api);
    putenv('APP_KEY=' . $key);
    putenv('APP_SECRET=' . $secret);

    $responseBody = json_encode([
        'codigo_cliente_omie' => 11118162834,
        'razao_social' => 'Cliente Teste',
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
            'geral/clientes/',
            [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'call' => 'ConsultarCliente',
                    'param' => [[
                        'codigo_cliente_omie' => 11118162834,
                        'codigo_cliente_integracao' => '',
                    ]],
                    'app_key' => $key,
                    'app_secret' => $secret,
                ],
            ]
        )
        ->willReturn($response);

    $service = new OmieClientService($httpClient);

    $result = $service->getClient(11118162834);

    expect($result)->toBeArray()
        ->and($result['codigo_cliente_omie'])->toBe(11118162834)
        ->and($result['razao_social'])->toBe('Cliente Teste');
});

it('throws when Omie credentials are missing', function (): void {
    $_ENV['APP_KEY'] = '';
    $_ENV['APP_SECRET'] = '';
    putenv('APP_KEY=');
    putenv('APP_SECRET=');

    $httpClient = $this->createMock(ClientInterface::class);
    $httpClient->expects($this->never())->method('request');

    $service = new OmieClientService($httpClient);

    $call = fn (): array => $service->getClient(11118162834);

    expect($call)->toThrow(RuntimeException::class, 'Missing Omie credentials in configuration.');
});

it('creates a client using IncluirCliente payload', function (): void {
    $api = 'https://app.omie.com.br/api/v1/';
    $key = 'test-app-key';
    $secret = 'test-app-secret';

    $_ENV['OMIE_API'] = $api;
    $_ENV['APP_KEY'] = $key;
    $_ENV['APP_SECRET'] = $secret;

    putenv('OMIE_API=' . $api);
    putenv('APP_KEY=' . $key);
    putenv('APP_SECRET=' . $secret);

    $clientPayload = [
        'codigo_cliente_integracao' => 'CodigoInterno0001',
        'email' => 'primeiro@ccliente.com.br',
        'razao_social' => 'Primeiro Cliente  Ltda Me',
        'nome_fantasia' => 'Primeiro Cliente',
        'cnpj_cpf' => '59872959048',
    ];

    $responseBody = json_encode([
        'codigo_cliente_omie' => 123456789,
        'codigo_cliente_integracao' => 'CodigoInterno0001',
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
            'geral/clientes/',
            [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'call' => 'IncluirCliente',
                    'param' => [$clientPayload],
                    'app_key' => $key,
                    'app_secret' => $secret,
                ],
            ]
        )
        ->willReturn($response);

    $service = new OmieClientService($httpClient);

    $result = $service->createClient($clientPayload);

    expect($result)->toBeArray()
        ->and($result['codigo_cliente_omie'])->toBe(123456789)
        ->and($result['codigo_cliente_integracao'])->toBe('CodigoInterno0001');
});
