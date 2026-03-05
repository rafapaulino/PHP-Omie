<?php

declare(strict_types=1);

namespace Rafapaulino\Omiephpsdk\Clients\Contracts;

interface RetrievesClientsInterface
{
    public function getClient(int|string $clientId): array;
}


