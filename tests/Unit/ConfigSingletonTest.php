<?php

declare(strict_types=1);

use Rafael\Omiephpsdk\Config\ConfigSingleton;

beforeEach(function (): void {
    resetConfigSingleton();
});

afterEach(function (): void {
    resetConfigSingleton();
});

it('returns the same singleton instance', function (): void {
    $first = ConfigSingleton::getInstance();
    $second = ConfigSingleton::getInstance();

    expect($first)->toBeInstanceOf(ConfigSingleton::class)
        ->and($second)->toBe($first);
});

it('returns config with required keys', function (): void {
    $config = ConfigSingleton::getInstance()->getConfig();

    expect($config)->toBeArray()
        ->and($config)->toHaveKeys(['omie_api', 'omie_key', 'omie_secret']);
});

it('uses existing environment values when available', function (): void {
    $api = 'https://example.test/api/v1/';
    $key = 'key-for-tests';
    $secret = 'secret-for-tests';

    $_ENV['OMIE_API'] = $api;
    $_ENV['APP_KEY'] = $key;
    $_ENV['APP_SECRET'] = $secret;

    putenv('OMIE_API=' . $api);
    putenv('APP_KEY=' . $key);
    putenv('APP_SECRET=' . $secret);

    $config = ConfigSingleton::getInstance()->getConfig();

    expect($config['omie_api'])->toBe($api)
        ->and($config['omie_key'])->toBe($key)
        ->and($config['omie_secret'])->toBe($secret);
});
