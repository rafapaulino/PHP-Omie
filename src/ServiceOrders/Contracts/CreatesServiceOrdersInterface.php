<?php

declare(strict_types=1);

namespace Rafapaulino\Omiephpsdk\ServiceOrders\Contracts;

interface CreatesServiceOrdersInterface
{
    /** @param array<string, mixed> $payload */
    public function createServiceOrder(array $payload): array;
}

