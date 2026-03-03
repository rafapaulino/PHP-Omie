<?php

declare(strict_types=1);

namespace Rafael\Omiephpsdk\Sellers\Contracts;

interface CreatesSellersInterface
{
    /** @param array<string, mixed> $payload */
    public function createSeller(array $payload): array;
}

