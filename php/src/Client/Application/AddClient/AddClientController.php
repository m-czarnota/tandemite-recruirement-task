<?php

declare(strict_types=1);

namespace App\Client\Application\AddClient;

use App\Client\Application\AddClient\File\RequestToUploadedFilesDtoMapper;
use App\Client\Application\AddClient\File\UploadedFilesDtoToClientFilesMapper;
use App\Client\Application\AddClient\Response\ClientToResponseDtoMapper;
use App\Client\Domain\AddClientService;
use App\Client\Domain\ClientFileNotValidException;
use App\Client\Domain\ClientFilesCountExceededException;
use App\Client\Domain\ClientNotValidException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/clients', methods: Request::METHOD_POST)]
class AddClientController extends AbstractController
{
    public function __construct(
        private readonly RequestValidator $requestValidator,
        private readonly RequestToClientMapper $requestToClientMapper,
        private readonly RequestToUploadedFilesDtoMapper $requestToUploadedFilesDtoMapper,
        private readonly UploadedFilesDtoToClientFilesMapper $uploadedFilesDtoToClientFilesMapper,
        private readonly EntityManagerInterface $entityManager,
        private readonly AddClientService $addClientService,
        private readonly ClientToResponseDtoMapper $clientToResponseDtoMapper,
    ) {
    }

    public function __invoke(): JsonResponse
    {
        $errors = $this->requestValidator->execute();
        if (!empty($errors)) {
            return new JsonResponse($errors, Response::HTTP_BAD_REQUEST);
        }

        try {
            $client = $this->requestToClientMapper->execute();
        } catch (ClientNotValidException $exception) {
            return new JsonResponse($exception->getMessage(), Response::HTTP_NOT_ACCEPTABLE, json: true);
        }

        $uploadedFilesDtoMapper = $this->requestToUploadedFilesDtoMapper->execute();

        try {
            $files = $this->uploadedFilesDtoToClientFilesMapper->execute(...$uploadedFilesDtoMapper);
        } catch (ClientFileNotValidException $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_NOT_ACCEPTABLE, json: true);
        }

        try {
            foreach ($files as $file) {
                $client->addFile($file);
            }
        } catch (ClientFilesCountExceededException $exception) {
            return new JsonResponse(['generalError' => $exception->getMessage()], Response::HTTP_NOT_ACCEPTABLE);
        }

        $client = $this->addClientService->execute($client);

        $this->entityManager->flush();

        return new JsonResponse($this->clientToResponseDtoMapper->execute($client), Response::HTTP_CREATED);
    }
}
