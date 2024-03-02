<?php

namespace App\Client\Domain;

use SplFileInfo;

class ClientFileDataDto
{
    public function __construct(
        public string $originalFilename,
        public SplFileInfo $fileInfo,
    ) {
    }
}