<?php

declare(strict_types=1);

namespace App\Tests\User\Behat;

use App\Tests\Common\Behat\TandemiteKernelBrowser;
use App\Tests\User\Stub\UserStub;
use App\User\Domain\UserPasswordHasherInterface;
use App\User\Domain\UserRepositoryInterface;
use Behat\Behat\Context\Context;
use Behat\Step\Given;
use Behat\Step\When;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

class LoggedUserContext implements Context
{
    private string $jwtToken = '';

    public function __construct(
        private readonly TandemiteKernelBrowser $browser,
        private readonly JWTTokenManagerInterface $JWTTokenManager,
        private readonly UserPasswordHasherInterface $userPasswordHasher,
        private readonly UserRepositoryInterface $userRepository,
    ) {
    }

    #[Given('there exist user with login :email and password :password')]
    public function thereExistUserWithLoginAndPassword(string $email, string $password): void
    {
        $user = UserStub::createExampleUser('example-user', $email);
        $existedUser = $this->userRepository->findOneByEmail($user->getUserIdentifier());

        if (!$existedUser) {
            $user->setPassword($this->userPasswordHasher->hash($user, $password));
            $this->userRepository->add($user);
        }

        $this->jwtToken = $this->JWTTokenManager->create($existedUser ?? $user);
    }

    #[When('I open :requestType page :url as logged user')]
    public function iOpenPageAsLoggedUser(string $requestType, string $url): void
    {
        $this->browser->json($requestType, $url, headers: [
            'HTTP_AUTHORIZATION' => "Bearer {$this->jwtToken}",
        ]);
    }
}
