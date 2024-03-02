<?php

namespace App\Client\Application\AddClient\Response;

class ResponseDto
{
    public function __construct(
        public string $id,
        public string $firstname,
        public string $lastname,

        /** @var array<int, ResponseClientFileDto> $files */
        public array $files = [],
    ) {
    }
}