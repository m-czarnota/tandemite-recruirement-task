<?php

namespace App\Client\Domain;

use JsonSerializable;
use Ramsey\Uuid\Uuid;

class ClientFile implements JsonSerializable
{
    public readonly string $id;

    /**
     * @param string|null $id
     * @param string $name
     * @param string $path
     * @throws ClientFileNotValidException
     */
    public function __construct(
        ?string $id,
        private string $name,
        private string $path,
    ) {
        $this->id = $id ?? Uuid::uuid7();

        $errors = $this->validate();
        if (!empty($errors)) {
            throw new ClientFileNotValidException(json_encode($errors));
        }
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'path' => $this->path,
        ];
    }

    public function update(self $clientFile): self
    {
        $this->name = $clientFile->getName();
        $this->path = $clientFile->getPath();

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    private function validate(): array
    {
        $errors = [];

        if (strlen($this->name) > 255) {
            $errors['name'] = 'Name cannot be longer than 255 characters';
        }

        if (empty($this->path)) {
            $errors['path'] = 'Path cannot be empty';
        }

        return $errors;
    }
}