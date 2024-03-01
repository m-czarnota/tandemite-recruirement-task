<?php

namespace App\Client\Domain;

readonly class AddClientService
{
    public function __construct(
        private ClientRepositoryInterface $clientRepository,
    ) {
    }

    public function execute(Client $client): Client
    {
        $this->clientRepository->add($client);

        return $client;
    }
}