<?php

declare(strict_types=1);

namespace Rafael\Omiephpsdk\PaymentMethods\Contracts;

interface ListsPaymentMethodsInterface
{
    /** @param array<string, mixed> $filters */
    public function listPaymentMethods(array $filters = []): array;
}

