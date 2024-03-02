<?php

namespace App\Common\Domain\Pagination;

interface PaginationResponseCreatorInterface
{
    public function execute(PaginationListDataDto $paginationListData, string $route): PaginationResponseInterface;
}