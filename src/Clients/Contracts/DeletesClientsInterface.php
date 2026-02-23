<?php

declare(strict_types=1);

namespace Rafael\Omiephpsdk\Clients\Contracts;

interface DeletesClientsInterface
{
    public function deleteClient(int|string $clientId): array;
}
