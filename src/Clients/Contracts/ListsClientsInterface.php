<?php

declare(strict_types=1);

namespace Rafael\Omiephpsdk\Clients\Contracts;

interface ListsClientsInterface
{
    /** @param array<string, mixed> $filters */
    public function listClients(array $filters = []): array;
}
