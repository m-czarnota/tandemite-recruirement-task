<?php

declare(strict_types=1);

namespace App\Client\Application\AddClient\File;

use App\Client\Domain\ClientFile;
use App\Client\Domain\ClientFileNotValidException;
use App\Client\Domain\FileValidator;

readonly class UploadedFilesDtoToClientFilesMapper
{
    public function __construct(
        private FileValidator $fileValidator,
    ) {
    }

    /**
     * @return array<int, ClientFile>
     *
     * @throws ClientFileNotValidException
     */
    public function execute(UploadedFileDto ...$uploadedFilesDto): array
    {
        $errors = [];
        $isError = false;
        $files = [];

        foreach ($uploadedFilesDto as $uploadedFileDto) {
            $fileErrors = $this->fileValidator->execute(
                $uploadedFileDto->size,
                $uploadedFileDto->mimeType,
            );
            if (!empty($fileErrors)) {
                $isError = true;
                $errors[] = $fileErrors;

                continue;
            }

            try {
                $clientFile = new ClientFile(
                    null,
                    $uploadedFileDto->clientName,
                    $uploadedFileDto->realPath,
                );
            } catch (ClientFileNotValidException $exception) {
                $isError = true;
                $errors[] = json_decode($exception->getMessage());

                continue;
            }

            $files[] = $clientFile;
            $errors[] = [];
        }

        if ($isError) {
            throw new ClientFileNotValidException(json_encode(['files' => $errors]));
        }

        return $files;
    }
}
