<?php

namespace App\Client\Domain;

interface ClientRepositoryInterface
{
    public function add(Client $client): void;

    public function findOneById(string $id): ?Client;

    /**
     * @param int $pageNumber
     * @param int $pageSize
     * @return array<int, Client>
     */
    public function findPage(int $pageNumber, int $pageSize): array;

    public function findCount(): int;
}