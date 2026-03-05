<?php

declare(strict_types=1);

namespace Rafapaulino\Omiephpsdk\Categories\Contracts;

interface ListsCategoriesInterface
{
    /** @param array<string, mixed> $filters */
    public function listCategories(array $filters = []): array;
}



