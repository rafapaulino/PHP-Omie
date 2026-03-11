<?php

declare(strict_types=1);

use GuzzleHttp\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Rafapaulino\Omiephpsdk\Services\OmieServiceService;

beforeEach(function (): void {
    resetConfigSingleton();
});

afterEach(function (): void {
    resetConfigSingleton();
});

it('gets services using ListarCadastroServico payload with merged filters', function (): void {
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
        'cadastro' => [
            ['cCodigo' => 'SVC001', 'cDescricao' => 'Servico 1'],
        ],
        'nTotPaginas' => 7,
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
            'servicos/servico/',
            [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'call' => 'ListarCadastroServico',
                    'param' => [[
                        'nPagina' => 7,
                        'nRegPorPagina' => 100,
                    ]],
                    'app_key' => $key,
                    'app_secret' => $secret,
                ],
            ]
        )
        ->willReturn($response);

    $service = new OmieServiceService($httpClient);

    $result = $service->listServices([
        'nPagina' => 7,
        'nRegPorPagina' => 100,
    ]);

    expect($result)->toBeArray()
        ->and($result['nTotPaginas'])->toBe(7)
        ->and($result['cadastro'][0]['cCodigo'])->toBe('SVC001');
});

it('throws when Omie credentials are missing', function (): void {
    $_ENV['APP_KEY'] = '';
    $_ENV['APP_SECRET'] = '';
    putenv('APP_KEY=');
    putenv('APP_SECRET=');

    $httpClient = $this->createMock(ClientInterface::class);
    $httpClient->expects($this->never())->method('request');

    $service = new OmieServiceService($httpClient);

    $call = fn (): array => $service->listServices();

    expect($call)->toThrow(RuntimeException::class, 'Missing Omie credentials in configuration.');
});
