<?php

declare(strict_types=1);

namespace Rafael\Omiephpsdk\Users\Contracts;

interface ListsUsersInterface
{
    /** @param array<string, mixed> $filters */
    public function listUsers(array $filters = []): array;
}

