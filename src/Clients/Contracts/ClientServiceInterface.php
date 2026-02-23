<?php

declare(strict_types=1);

namespace Rafael\Omiephpsdk\Clients\Contracts;

interface ClientServiceInterface extends
    CreatesClientsInterface,
    RetrievesClientsInterface,
    UpdatesClientsInterface,
    DeletesClientsInterface,
    ListsClientsInterface
{
}
