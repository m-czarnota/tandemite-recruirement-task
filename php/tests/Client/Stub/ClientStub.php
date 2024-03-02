<?php

declare(strict_types=1);

namespace App\Tests\Client\Stub;

use App\Client\Domain\Client;
use App\Client\Domain\ClientFileNotValidException;
use App\Client\Domain\ClientFilesCountExceededException;
use App\Client\Domain\ClientNotValidException;

class ClientStub
{
    /**
     * @throws ClientFileNotValidException
     * @throws ClientFilesCountExceededException
     * @throws ClientNotValidException
     */
    public static function createFromArrayData(array $clientData): Client
    {
        $client = new Client(
            $clientData['id'] ?? null,
            $clientData['firstname'],
            $clientData['lastname'],
        );

        foreach ($clientData['files'] as $fileData) {
            $clientFile = ClientFileStub::createFromArrayData($fileData);
            $client->addFile($clientFile);
        }

        return $client;
    }

    /**
     * @throws ClientNotValidException
     */
    public static function createExample(?string $id = null): Client
    {
        return new Client(
            $id,
            'Example',
            'Client',
        );
    }
}
