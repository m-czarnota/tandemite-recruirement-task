<?php

namespace App\Client\Domain;

use App\Common\Domain\File\FileUploaderInterface;

readonly class AddClientService
{
    public function __construct(
        private ClientRepositoryInterface $clientRepository,
        private FileUploaderInterface $fileUploader,
    ) {
    }

    /**
     * @throws ClientFileNotValidException
     */
    public function execute(Client $client): Client
    {
        foreach ($client->getFiles() as $clientFile) {
            $newFilePath = $this->fileUploader->execute($clientFile->getPath());
            $updatedClientFile = new ClientFile(
                $clientFile->id,
                $clientFile->getName(),
                $newFilePath,
            );

            $client->updateFile($updatedClientFile);
        }

        $this->clientRepository->add($client);

        return $client;
    }
}