<?php

declare(strict_types=1);

namespace App\Client\Application\AddClient\Response;

class ResponseClientFileDto
{
    public function __construct(
        public string $id,
        public string $name,
        public string $path,
    ) {
    }
}
