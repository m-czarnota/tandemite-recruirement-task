<?php

namespace App\Tests\Client\Unit\Domain;

use App\Client\Domain\Client;
use App\Client\Domain\ClientFileNotValidException;
use App\Client\Domain\ClientFilesCountExceededException;
use App\Client\Domain\ClientNotValidException;
use App\Tests\Client\Stub\ClientFileStub;
use App\Tests\Client\Stub\ClientStub;
use PHPUnit\Framework\TestCase;

class ClientTest extends TestCase
{
    /**
     * @throws ClientNotValidException
     * @dataProvider constructValidationsDataProvider
     */
    public function testConstructValidations(array $clientData, string $exception, string $exceptionMessage): void
    {
        self::expectException($exception);
        self::expectExceptionMessage($exceptionMessage);

        new Client(
            $clientData['id'],
            $clientData['firstname'],
            $clientData['lastname'],
        );
    }

    public static function constructValidationsDataProvider(): array
    {
        return [
            'empty fields' => [
                'clientData' => [
                    'id' => '',
                    'firstname' => '',
                    'lastname' => '',
                ],
                'exception' => ClientNotValidException::class,
                'exceptionMessage' => json_encode([
                    'id' => 'Id cannot be empty',
                    'firstname' => 'Firstname cannot be empty',
                    'lastname' => 'Lastname cannot be empty',
                ]),
            ],
            'too long fields' => [
                'clientData' => [
                    'id' => 'too long too long too long too long too long too long too long too long too long too long',
                    'firstname' => 'too long too long too long too long too long too long too long too long too long too long',
                    'lastname' => 'too long too long too long too long too long too long too long too long too long too long',
                ],
                'exception' => ClientNotValidException::class,
                'exceptionMessage' => json_encode([
                    'id' => 'Id cannot be longer than 50 characters',
                    'firstname' => 'Firstname cannot be longer than 80 characters',
                    'lastname' => 'Lastname cannot be longer than 80 characters',
                ]),
            ],
        ];
    }

    /**
     * @param array $clientData
     * @param array $clientFileDataToAdd
     * @param int $exceptedFileCount
     * @return void
     * @throws ClientNotValidException
     * @throws ClientFileNotValidException
     * @throws ClientFilesCountExceededException
     *
     * @dataProvider addFileDataProvider
     */
    public function testAddFile(array $clientData, array $clientFileDataToAdd, int $exceptedFileCount): void
    {
        $client = ClientStub::createFromArrayData($clientData);
        $clientFileToAdd = ClientFileStub::createFromArrayData($clientFileDataToAdd);

        $client->addFile($clientFileToAdd);
        self::assertCount($exceptedFileCount, $client->getFiles());
    }

    public static function addFileDataProvider(): array
    {
        return [
            'add 1 file to client with 0 files' => [
                'clientData' => [
                    'id' => null,
                    'firstname' => 'Jan',
                    'lastname' => 'Kowalski',
                    'files' => [],
                ],
                'clientFileDataToAdd' => [
                    'id' => null,
                    'name' => 'funny-cats.jpg',
                    'path' => 'jan-kowalski-1023-234435',
                ],
                'exceptedFileCount' => 1,
            ],
        ];
    }

    /**
     * @param array $clientData
     * @param array $clientFileDataToAdd
     * @param string $exception
     * @param string $exceptionMessage
     * @return void
     * @throws ClientFileNotValidException
     * @throws ClientFilesCountExceededException
     * @throws ClientNotValidException
     *
     * @dataProvider addFileValidationsDataProvider
     */
    public function testAddFileValidations(
        array $clientData,
        array $clientFileDataToAdd,
        string $exception,
        string $exceptionMessage
    ): void {
        $client = ClientStub::createFromArrayData($clientData);
        $clientFileToAdd = ClientFileStub::createFromArrayData($clientFileDataToAdd);

        self::expectException($exception);
        self::expectExceptionMessage($exceptionMessage);

        $client->addFile($clientFileToAdd);
    }

    public static function addFileValidationsDataProvider(): array
    {
        $allowedFilesCount = Client::ALLOWED_FILES_COUNT;

        return [
            'attempt to add file when there are max allowed file count' => [
                'clientData' => [
                    'id' => null,
                    'firstname' => 'Jan',
                    'lastname' => 'Kowalski',
                    'files' => [
                        [
                            'id' => null,
                            'name' => 'funny-dogs.jpg',
                            'path' => 'jan-smith-10d23-2344fdg35',
                        ],
                    ],
                ],
                'clientFileDataToAdd' => [
                    'id' => null,
                    'name' => 'funny-cats.jpg',
                    'path' => 'jan-kowalski-1023-234435',
                ],
                'exception' => ClientFilesCountExceededException::class,
                'exceptionMessage' => "Client cannot have more than $allowedFilesCount files",
            ],
        ];
    }
}