<?php

namespace App\Client\Domain;

use App\Common\Domain\Pagination\PaginationListDataDto;

readonly class ListClientService
{
    public function __construct(
        private ClientRepositoryInterface $clientRepository,
    ) {
    }

    public function execute(int $paginationPage, int $paginationLimit): PaginationListDataDto
    {
        $records = $this->clientRepository->findPage($paginationPage, $paginationLimit);
        $totalRecords = $this->clientRepository->findCount();

        return new PaginationListDataDto(
            $records,
            $totalRecords,
            $paginationPage,
            $paginationLimit,
        );
    }
}