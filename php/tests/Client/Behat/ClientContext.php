<?php

namespace App\Tests\Client\Behat;

use App\Tests\Common\TandemiteKernelBrowser;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Step\Then;
use PHPUnit\Framework\Assert;

readonly class ClientContext implements Context
{
    public function __construct(
        private TandemiteKernelBrowser $browser,
    ) {
    }

    #[Then('the response should looks like')]
    public function theResponseShouldLooksLike(PyStringNode $dummyResponse): void
    {
        $dummyResponseFields = json_decode(trim($dummyResponse->getRaw()), true);
        $response = $this->browser->getLastResponseContentAsArray();

        $this->checkIfResponseLooksLikeDummyResponse($response, $dummyResponseFields);
    }

    #[Then('the response should contains message :message')]
    public function theResponseShouldContainsMessage(string $message): void
    {
        $response = trim($this->browser->getLastResponseContent());
        $responseMessage = json_decode($response);

        Assert::assertEquals($message, $responseMessage);
    }

    private function checkIfResponseLooksLikeDummyResponse(array $response, array $dummyResponse): void
    {
        $specialNonExistedDataInResponse = '!not exists@';

        foreach ($dummyResponse as $dummyResponseIndex => $dummyResponseData) {
            $responseData = array_key_exists($dummyResponseIndex, $response)
                ? $response[$dummyResponseIndex]
                : $specialNonExistedDataInResponse;

            if (is_bool($responseData)) {
                Assert::assertEquals($specialNonExistedDataInResponse, $responseData);
            } else {
                Assert::assertNotEquals($specialNonExistedDataInResponse, $responseData);
            }
            Assert::assertEquals(gettype($dummyResponseData), gettype($responseData));

            if (is_array($dummyResponseData)) {
                $this->checkIfResponseLooksLikeDummyResponse($responseData, $dummyResponseData);
            }
        }
    }
}