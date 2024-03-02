<?php

declare(strict_types=1);

namespace App\Client\Domain;

use App\Common\Domain\File\FileGetterInterface;
use App\Common\Domain\File\FileNotExistException;

readonly class GetClientFileService
{
    public function __construct(
        private ClientRepositoryInterface $clientRepository,
        private FileGetterInterface $fileGetter,
    ) {
    }

    /**
     * @throws ClientFileNotFoundException
     * @throws FileNotExistException
     */
    public function execute(string $clientId, string $fileId): ClientFileDataDto
    {
        $client = $this->clientRepository->findOneById($clientId);
        $clientFile = $client?->getFile($fileId);

        if (!$clientFile) {
            throw new ClientFileNotFoundException("Not found file `$fileId` for client `$clientId`");
        }

        return new ClientFileDataDto(
            $clientFile->getName(),
            $this->fileGetter->execute($clientFile->getPath()),
        );
    }
}
