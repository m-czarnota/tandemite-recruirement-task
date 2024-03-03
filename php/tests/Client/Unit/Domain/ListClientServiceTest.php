<?php

declare(strict_types=1);

namespace App\Tests\Client\Unit\Domain;

use App\Client\Domain\ClientNotValidException;
use App\Client\Domain\ClientRepositoryInterface;
use App\Client\Domain\ListClientService;
use App\Tests\Client\Stub\ClientStub;
use PHPUnit\Framework\TestCase;

class ListClientServiceTest extends TestCase
{
    private readonly ClientRepositoryInterface $clientRepository;
    private readonly ListClientService $service;

    protected function setUp(): void
    {
        $this->clientRepository = $this->createMock(ClientRepositoryInterface::class);
        $this->service = new ListClientService(
            $this->clientRepository,
        );
    }

    /**
     * @throws ClientNotValidException
     *
     * @dataProvider executeDataProvider
     */
    public function testExecute(
        int $totalRecords,
        int $paginationPage,
        int $paginationLimit,
        int $exceptedRecordCount,
    ): void {
        $totalPages = intval(ceil($totalRecords / $paginationLimit));
        if ($paginationPage < $totalPages) {
            $recordCountOnPage = $paginationLimit;
        } elseif ($paginationPage === $totalPages) {
            $recordCountOnPage = $totalRecords % $paginationLimit;
        } else {
            $recordCountOnPage = 0;
        }

        $exampleClients = [];
        for ($i = 0; $i < $recordCountOnPage; ++$i) {
            $exampleClients[] = ClientStub::createExample();
        }

        $this->clientRepository
            ->method('findPage')
            ->willReturn($exampleClients);

        $this->clientRepository
            ->method('findCount')
            ->willReturn($totalRecords);

        $paginationListDataDto = $this->service->execute($paginationPage, $paginationLimit);
        self::assertCount($exceptedRecordCount, $paginationListDataDto->records);
    }

    public static function executeDataProvider(): array
    {
        return [
            '8 total records, 2nd page, 3 records' => [
                'totalRecords' => 8,
                'paginationPage' => 2,
                'paginationLimit' => 3,
                'exceptedRecordCount' => 3,
            ],
            '8 total records, 3rd page, 2 records' => [
                'totalRecords' => 8,
                'paginationPage' => 3,
                'paginationLimit' => 3,
                'exceptedRecordCount' => 2,
            ],
            '8 total records, 5th page, 0 records' => [
                'totalRecords' => 8,
                'paginationPage' => 5,
                'paginationLimit' => 3,
                'exceptedRecordCount' => 0,
            ],
        ];
    }
}
