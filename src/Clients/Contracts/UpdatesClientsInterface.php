<?php

declare(strict_types=1);

namespace Rafael\Omiephpsdk\Clients\Contracts;

interface UpdatesClientsInterface
{
    /** @param array<string, mixed> $payload */
    public function updateClient(int|string $clientId, array $payload): array;
}
