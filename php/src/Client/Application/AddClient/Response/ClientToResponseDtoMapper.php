<?php

declare(strict_types=1);

namespace App\Client\Application\AddClient\Response;

use App\Client\Domain\Client;
use App\Common\Domain\Route\RouteGeneratorInterface;
use App\Common\Domain\Route\RouteTypeEnum;

readonly class ClientToResponseDtoMapper
{
    public function __construct(
        private RouteGeneratorInterface $routeGenerator,
    ) {
    }

    public function execute(Client $client): ResponseDto
    {
        $filesDto = [];
        foreach ($client->getFiles() as $clientFile) {
            $absoluteFilePath = $this->routeGenerator->execute(
                'api:v1:get_client_file',
                RouteTypeEnum::ABSOLUTE_URL,
                [
                    'clientId' => $client->id,
                    'fileId' => $clientFile->id,
                ],
            );

            $filesDto[] = new ResponseClientFileDto(
                $clientFile->id,
                $clientFile->getName(),
                $absoluteFilePath,
            );
        }

        return new ResponseDto(
            $client->id,
            $client->firstname,
            $client->lastname,
            $filesDto,
        );
    }
}
