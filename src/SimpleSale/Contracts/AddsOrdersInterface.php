<?php

declare(strict_types=1);

namespace Rafapaulino\Omiephpsdk\SimpleSale\Contracts;

interface AddsOrdersInterface
{
    /** @param array<string, mixed> $payload */
    public function addOrder(array $payload): array;
}


