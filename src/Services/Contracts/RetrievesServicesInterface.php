<?php

declare(strict_types=1);

namespace Rafapaulino\Omiephpsdk\Services\Contracts;

interface RetrievesServicesInterface
{
    /** @param array<string, mixed> $filters */
    public function listServices(array $filters = []): array;
}
