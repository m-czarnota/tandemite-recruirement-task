<?php

namespace App\Tests\Client\Unit\Domain;

use App\Client\Domain\FileValidator;
use PHPUnit\Framework\TestCase;

class FileValidatorTest extends TestCase
{
    private readonly FileValidator $service;

    protected function setUp(): void
    {
        $this->service = new FileValidator();
    }

    /**
     * @param int $size
     * @param string $mimeType
     * @param array $exceptedErrors
     * @return void
     * @dataProvider executeDataProvider
     */
    public function testExecute(int $size, string $mimeType, array $exceptedErrors): void
    {
        $errors = $this->service->execute($size, $mimeType);
        self::assertCount(count($exceptedErrors), $errors);

        foreach ($exceptedErrors as $errorName => $errorMessage) {
            self::assertArrayHasKey($errorName, $errors);
            self::assertEquals($errors[$errorName], $errorMessage);
        }
    }

    public static function executeDataProvider(): array
    {
        $maxSize = FileValidator::MAX_SIZE;

        return [
            'no errors' => [
                'size' => 80,
                'mimeType' => 'image/jpeg',
                'exceptedErrors' => [],
            ],
            'too large file and wrong mime type' => [
                'size' => 423543623543,
                'mimeType' => 'application/pdf',
                'exceptedErrors' => [
                    'size' => "File is too large, allowed size: `$maxSize`, current size: `423543623543`",
                    'mimeType' => 'File is not image',
                ],
            ],
            'non-existed kind of image' => [
                'size' => 24325,
                'mimeType' => 'image/non-existed-image-type',
                'exceptedErrors' => [],
            ],
        ];
    }
}