<?php

declare(strict_types=1);

namespace Rafapaulino\Omiephpsdk\Products\Contracts;

interface ListsProductsInterface
{
    /** @param array<string, mixed> $filters */
    public function listProducts(array $filters = []): array;
}



