<?php

declare(strict_types=1);

namespace App\Client\Application\ListClient;

use App\Client\Domain\Client;
use App\Client\Domain\ListClientService;
use App\Common\Domain\Pagination\PaginationResponseCreatorInterface;
use App\Common\Domain\Route\RouteGeneratorInterface;
use App\Common\Domain\Route\RouteTypeEnum;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Attribute\Route;

#[Route(name: 'api:v1:clients:list', methods: Request::METHOD_GET)]
class ListClientController extends AbstractController
{
    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly ParameterBagInterface $parameterBag,
        private readonly ListClientService $service,
        private readonly RouteGeneratorInterface $routeGenerator,
        private readonly PaginationResponseCreatorInterface $paginationResponseCreator,
    ) {
    }

    public function __invoke(): JsonResponse
    {
        $request = $this->requestStack->getCurrentRequest();

        $paginationPage = (int) $request->get('page', $this->parameterBag->get('pagination.default_page'));
        $paginationLimit = (int) $request->get('limit', $this->parameterBag->get('pagination.default_limit'));

        $paginationListDataDto = $this->service->execute($paginationPage, $paginationLimit);
        $paginationListDataDto->records = array_map(function (Client $client) {
            $serializedClient = $client->jsonSerialize();
            $clientId = $client->id;

            $serializedClient['files'] = array_map(function (array $clientFileSerialized) use ($clientId) {
                $routeParams = [
                    'clientId' => $clientId,
                    'fileId' => $clientFileSerialized['id'],
                ];
                $clientFileSerialized['path'] = $this->routeGenerator->execute(
                    'api:v1:get_client_file',
                    RouteTypeEnum::ABSOLUTE_URL,
                    $routeParams,
                );

                return $clientFileSerialized;
            }, $serializedClient['files']);

            return $serializedClient;
        }, $paginationListDataDto->records);

        return $this->paginationResponseCreator->execute($paginationListDataDto, 'api:v1:clients:list');
    }
}
