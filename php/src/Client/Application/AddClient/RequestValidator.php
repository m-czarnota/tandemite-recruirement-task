<?php

namespace App\Client\Application\AddClient;

use Symfony\Component\HttpFoundation\RequestStack;

readonly class RequestValidator
{
    public function __construct(
        private RequestStack $requestStack,
    ) {
    }

    public function execute(): array
    {
        $request = $this->requestStack->getCurrentRequest();
        $errors = [];

        if ($request->get('firstname') === null) {
            $errors['firstname'] = 'Missing `firstname` parameter';
        }
        if ($request->get('lastname') === null) {
            $errors['lastname'] = 'Missing `lastname` parameter';
        }

        return $errors;
    }
}