<?php

namespace App\Tests\Client\Stub;

use App\Client\Application\AddClient\File\UploadedFileDto;

class UploadedFileDtoStub
{
    /**
     * @param array $uploadedFilesDtoData
     * @return array<int, UploadedFileDto>
     */
    public static function createMultipleFromArrayData(array $uploadedFilesDtoData): array
    {
        return array_map(
            fn(array $uploadedFileDtoData) => self::createFromArrayData($uploadedFileDtoData),
            $uploadedFilesDtoData
        );
    }

    public static function createFromArrayData(array $uploadedFileDtoData): UploadedFileDto
    {
        return new UploadedFileDto(
            $uploadedFileDtoData['clientName'],
            $uploadedFileDtoData['mimeType'],
            $uploadedFileDtoData['size'],
            $uploadedFileDtoData['realPath'],
        );
    }
}