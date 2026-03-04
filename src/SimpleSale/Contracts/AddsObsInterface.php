<?php

declare(strict_types=1);

namespace Rafael\Omiephpsdk\SimpleSale\Contracts;

interface AddsObsInterface
{
    /** @param array<string, mixed> $payload */
    public function addObs(array $payload): array;
}
