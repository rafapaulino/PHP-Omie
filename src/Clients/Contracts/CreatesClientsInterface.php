<?php

declare(strict_types=1);

namespace Rafapaulino\Omiephpsdk\Clients\Contracts;

interface CreatesClientsInterface
{
    /** @param array<string, mixed> $payload */
    public function createClient(array $payload): array;
}


