<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Pagination;

use App\Common\Domain\Pagination\PaginationListDataDto;
use App\Common\Domain\Pagination\PaginationResponseCreatorInterface;
use App\Common\Domain\Pagination\PaginationResponseInterface;

readonly class SymfonyPaginationResponseCreator implements PaginationResponseCreatorInterface
{
    public function execute(PaginationListDataDto $paginationListData, string $route): PaginationResponseInterface
    {
        return new SymfonyPaginationResponse($paginationListData);
    }
}
