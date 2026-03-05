<?php

declare(strict_types=1);

namespace Rafapaulino\Omiephpsdk\SimpleSale\Contracts;

interface ListsOrdersInterface
{
    /** @param array<string, mixed> $filters */
    public function listOrders(array $filters = []): array;
}


