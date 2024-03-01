<?php

namespace App\Tests\Client\Stub;

use App\Client\Domain\Client;
use App\Client\Domain\ClientFileNotValidException;
use App\Client\Domain\ClientFilesCountExceededException;
use App\Client\Domain\ClientNotValidException;

class ClientStub
{
    /**
     * @param array $clientData
     * @return Client
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
}