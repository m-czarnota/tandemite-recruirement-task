<?php

namespace App\Client\Domain;

interface ClientRepositoryInterface
{
    public function add(Client $client): void;
}