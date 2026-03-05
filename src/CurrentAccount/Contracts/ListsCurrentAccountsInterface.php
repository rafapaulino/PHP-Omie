<?php

declare(strict_types=1);

namespace Rafapaulino\Omiephpsdk\CurrentAccount\Contracts;

interface ListsCurrentAccountsInterface
{
    /** @param array<string, mixed> $filters */
    public function listCurrentAccounts(array $filters = []): array;
}



