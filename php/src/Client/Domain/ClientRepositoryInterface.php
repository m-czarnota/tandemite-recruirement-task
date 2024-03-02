<?php

declare(strict_types=1);

namespace App\Client\Domain;

interface ClientRepositoryInterface
{
    public function add(Client $client): void;

    public function findOneById(string $id): ?Client;

    /**
     * @return array<int, Client>
     */
    public function findPage(int $pageNumber, int $pageSize): array;

    public function findCount(): int;
}
