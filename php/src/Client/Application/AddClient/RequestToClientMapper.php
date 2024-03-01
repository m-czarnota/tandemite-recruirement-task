<?php

namespace App\Client\Application\AddClient;

use App\Client\Domain\Client;
use App\Client\Domain\ClientNotValidException;
use Symfony\Component\HttpFoundation\RequestStack;

readonly class RequestToClientMapper
{
    public function __construct(
        private RequestStack $requestStack,
    ) {
    }

    /**
     * @throws ClientNotValidException
     */
    public function execute(): Client
    {
        $request = $this->requestStack->getCurrentRequest();

        return new Client(
            null,
            $request->get('firstname', ''),
            $request->get('lastname', ''),
        );
    }
}