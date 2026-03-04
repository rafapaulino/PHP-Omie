<?php

declare(strict_types=1);

namespace Rafael\Omiephpsdk\BillingSteps\Contracts;

interface ListsBillingStepsInterface
{
    /** @param array<string, mixed> $filters */
    public function listBillingSteps(array $filters = []): array;
}
