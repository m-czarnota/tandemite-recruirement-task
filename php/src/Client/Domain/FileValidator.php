<?php

declare(strict_types=1);

namespace App\Client\Domain;

class FileValidator
{
    /**
     * size in bytes.
     */
    public const int MAX_SIZE = 2097152;
    public const string IMAGE_MIME_TYPE_PATTERN = 'image/';

    public function execute(int $size, string $mimeType): array
    {
        $errors = [];

        $maxSize = self::MAX_SIZE;
        if ($size > $maxSize) {
            $errors['size'] = "File is too large, allowed size: `$maxSize`, current size: `$size`";
        }

        if (!str_starts_with($mimeType, self::IMAGE_MIME_TYPE_PATTERN)) {
            $errors['mimeType'] = 'File is not image';
        }

        return $errors;
    }
}
