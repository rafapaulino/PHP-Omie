<?php

declare(strict_types=1);

use Rafael\Omiephpsdk\Config\ConfigSingleton;

function resetConfigSingleton(): void
{
    $reflection = new ReflectionClass(ConfigSingleton::class);
    $instanceProperty = $reflection->getProperty('instance');
    $instanceProperty->setAccessible(true);
    $instanceProperty->setValue(null, null);
}
