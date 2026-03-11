<?php

declare(strict_types=1);

use GuzzleHttp\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Rafapaulino\Omiephpsdk\ServiceOrders\OmieServiceOrderService;

beforeEach(function (): void {
    resetConfigSingleton();
});

afterEach(function (): void {
    resetConfigSingleton();
});

it('creates a service order using IncluirOS payload', function (): void {
    $api = 'https://app.omie.com.br/api/v1/';
    $key = 'test-app-key';
    $secret = 'test-app-secret';

    $_ENV['OMIE_API'] = $api;
    $_ENV['APP_KEY'] = $key;
    $_ENV['APP_SECRET'] = $secret;

    putenv('OMIE_API=' . $api);
    putenv('APP_KEY=' . $key);
    putenv('APP_SECRET=' . $secret);

    $osPayload = [
        'Cabecalho' => [
            'cCodIntOS' => '1739110326',
            'cCodParc' => '999',
            'cEtapa' => '10',
            'dDtPrevisao' => '11/03/2026',
            'nCodCli' => 3203383223,
            'nQtdeParc' => 1,
        ],
        'Observacoes' => [
            'cObsOS' => 'teste',
        ],
        'InformacoesAdicionais' => [
            'cCidPrestServ' => 'SAO PAULO (SP)',
            'cCodCateg' => '1.01.02',
            'cDadosAdicNF' => 'OS incluida via API de teste 17:24',
            'nCodCC' => 3203383209,
        ],
        'ServicosPrestados' => [[
            'nCodServico' => 3203530838,
            'nQtde' => 1,
            'nValUnit' => 0,
        ]],
    ];

    $responseBody = json_encode([
        'cCodIntOS' => '1739110326',
        'codigo_status' => '0',
        'descricao_status' => 'OS incluida com sucesso!',
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
            'servicos/os/',
            [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'call' => 'IncluirOS',
                    'param' => [$osPayload],
                    'app_key' => $key,
                    'app_secret' => $secret,
                ],
            ]
        )
        ->willReturn($response);

    $service = new OmieServiceOrderService($httpClient);

    $result = $service->createServiceOrder($osPayload);

    expect($result)->toBeArray()
        ->and($result['codigo_status'])->toBe('0')
        ->and($result['cCodIntOS'])->toBe('1739110326');
});

it('throws when Omie credentials are missing', function (): void {
    $_ENV['APP_KEY'] = '';
    $_ENV['APP_SECRET'] = '';
    putenv('APP_KEY=');
    putenv('APP_SECRET=');

    $httpClient = $this->createMock(ClientInterface::class);
    $httpClient->expects($this->never())->method('request');

    $service = new OmieServiceOrderService($httpClient);

    $call = fn (): array => $service->createServiceOrder([]);

    expect($call)->toThrow(RuntimeException::class, 'Missing Omie credentials in configuration.');
});

