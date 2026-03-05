<?php

declare(strict_types=1);

namespace Rafapaulino\Omiephpsdk\Sellers\Contracts;

interface ListsSellersInterface
{
    /** @param array<string, mixed> $filters */
    public function listSellers(array $filters = []): array;
}



