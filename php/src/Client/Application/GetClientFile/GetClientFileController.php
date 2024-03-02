<?php

declare(strict_types=1);

namespace App\Client\Application\GetClientFile;

use App\Client\Domain\ClientFileNotFoundException;
use App\Client\Domain\GetClientFileService;
use App\Common\Domain\File\FileNotExistException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/{clientId}/files/{fileId}', name: 'api:v1:get_client_file', requirements: ['clientId' => '\S+', 'fileId' => '\S+'], methods: Request::METHOD_GET)]
class GetClientFileController extends AbstractController
{
    public function __construct(
        private readonly GetClientFileService $service,
    ) {
    }

    /**
     * @throws ClientFileNotFoundException
     * @throws FileNotExistException
     */
    public function __invoke(string $clientId, string $fileId): BinaryFileResponse
    {
        // handle error as not found
        $fileDataDto = $this->service->execute($clientId, $fileId);
        $fileInfo = $fileDataDto->fileInfo;

        $response = new BinaryFileResponse($fileInfo);
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $fileDataDto->originalFilename);
        $response->headers->set('Content-Type', mime_content_type($fileInfo->getRealPath()));

        return $response;
    }
}
