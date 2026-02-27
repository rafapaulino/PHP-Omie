<?php

declare(strict_types=1);

namespace Rafael\Omiephpsdk\CFOP\Contracts;

interface ListsCfopInterface
{
    /** @param array<string, mixed> $filters */
    public function listCfop(array $filters = []): array;
}

