<?php

declare(strict_types=1);

namespace App\Tests\Client\Stub;

use App\Client\Domain\ClientFile;
use App\Client\Domain\ClientFileNotValidException;

class ClientFileStub
{
    /**
     * @return array<int, ClientFile>
     *
     * @throws ClientFileNotValidException
     */
    public static function createMultipleFromArrayData(array $clientFilesData): array
    {
        return array_map(fn (array $clientFileData) => self::createFromArrayData($clientFileData), $clientFilesData);
    }

    /**
     * @throws ClientFileNotValidException
     */
    public static function createFromArrayData(array $clientFileData): ClientFile
    {
        return new ClientFile(
            $clientFileData['id'] ?? null,
            $clientFileData['name'],
            $clientFileData['path'],
        );
    }
}
