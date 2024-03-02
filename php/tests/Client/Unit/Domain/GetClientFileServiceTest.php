<?php

declare(strict_types=1);

namespace App\Tests\Client\Unit\Domain;

use App\Client\Domain\ClientFileNotFoundException;
use App\Client\Domain\ClientFileNotValidException;
use App\Client\Domain\ClientFilesCountExceededException;
use App\Client\Domain\ClientNotValidException;
use App\Client\Domain\ClientRepositoryInterface;
use App\Client\Domain\GetClientFileService;
use App\Common\Domain\File\FileGetterInterface;
use App\Common\Domain\File\FileNotExistException;
use App\Tests\Client\Stub\ClientStub;
use PHPUnit\Framework\TestCase;
use SplFileInfo;

class GetClientFileServiceTest extends TestCase
{
    private readonly ClientRepositoryInterface $clientRepository;
    private readonly FileGetterInterface $fileGetter;
    private readonly GetClientFileService $service;

    protected function setUp(): void
    {
        $this->clientRepository = $this->createMock(ClientRepositoryInterface::class);
        $this->fileGetter = $this->createMock(FileGetterInterface::class);

        $this->service = new GetClientFileService(
            $this->clientRepository,
            $this->fileGetter,
        );
    }

    /**
     * @throws ClientFileNotFoundException
     * @throws ClientFileNotValidException
     * @throws ClientFilesCountExceededException
     * @throws ClientNotValidException
     * @throws FileNotExistException
     *
     * @dataProvider executeDataProvider
     */
    public function testExecute(array $clientData, string $clientId, string $fileId, string $exceptedOriginalFilename): void
    {
        $client = ClientStub::createFromArrayData($clientData);
        $this->clientRepository
            ->method('findOneById')
            ->willReturnCallback(fn (string $param) => is_numeric($param) ? $client : null);

        $this->fileGetter
            ->method('execute')
            ->willReturn(new SplFileInfo('example'));

        $clientFileDataDto = $this->service->execute($clientId, $fileId);
        self::assertEquals($exceptedOriginalFilename, $clientFileDataDto->originalFilename);
    }

    public static function executeDataProvider(): array
    {
        return [
            'client with 2 files' => [
                'clientData' => [
                    'id' => '1',
                    'firstname' => 'Jan',
                    'lastname' => 'Kowalski',
                    'files' => [
                        [
                            'id' => '1.1',
                            'name' => 'teddy-bear.jpg',
                            'path' => 'path',
                        ],
                    ],
                ],
                'clientId' => '1',
                'fileId' => '1.1',
                'exceptedOriginalFilename' => 'teddy-bear.jpg',
            ],
        ];
    }

    /**
     * @throws ClientFileNotFoundException
     * @throws ClientFileNotValidException
     * @throws ClientFilesCountExceededException
     * @throws ClientNotValidException
     * @throws FileNotExistException
     *
     * @dataProvider executeValidationsDataProvider
     */
    public function testExecuteValidations(
        array $clientData,
        string $clientId,
        string $fileId,
        string $exception,
        string $exceptionMessage
    ): void {
        $client = ClientStub::createFromArrayData($clientData);
        $this->clientRepository
            ->method('findOneById')
            ->willReturnCallback(fn (string $param) => $client->id === $param ? $client : null);

        self::expectException($exception);
        self::expectExceptionMessage($exceptionMessage);

        $this->service->execute($clientId, $fileId);
    }

    public static function executeValidationsDataProvider(): array
    {
        return [
            'client does not exist' => [
                'clientData' => [
                    'id' => '1',
                    'firstname' => 'Jan',
                    'lastname' => 'Kowalski',
                    'files' => [
                        [
                            'id' => '1.1',
                            'name' => 'teddy-bear.jpg',
                            'path' => 'path',
                        ],
                    ],
                ],
                'clientId' => '234',
                'fileId' => '1.1',
                'exception' => ClientFileNotFoundException::class,
                'exceptionMessage' => 'Not found file `1.1` for client `234`',
            ],
            'client file not found' => [
                'clientData' => [
                    'id' => '1',
                    'firstname' => 'Jan',
                    'lastname' => 'Kowalski',
                    'files' => [
                        [
                            'id' => '1.1',
                            'name' => 'teddy-bear.jpg',
                            'path' => 'path',
                        ],
                    ],
                ],
                'clientId' => '1',
                'fileId' => '1.231',
                'exception' => ClientFileNotFoundException::class,
                'exceptionMessage' => 'Not found file `1.231` for client `1`',
            ],
        ];
    }
}
