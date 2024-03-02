<?php

declare(strict_types=1);

namespace App\Client\Domain;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use JsonSerializable;
use Ramsey\Uuid\Uuid;

class Client implements JsonSerializable
{
    public const int ALLOWED_FILES_COUNT = 1;

    public readonly string $id;

    /** @var ArrayCollection<int, ClientFile> */
    private Collection $files;

    /**
     * @throws ClientNotValidException
     */
    public function __construct(
        ?string $id,
        public readonly string $firstname,
        public readonly string $lastname,
    ) {
        $this->id = $id ?? Uuid::uuid7()->toString();
        $this->files = new ArrayCollection();

        $errors = $this->validate();
        if (!empty($errors)) {
            throw new ClientNotValidException(json_encode($errors));
        }
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'files' => array_map(fn (ClientFile $file) => $file->jsonSerialize(), $this->getFiles()),
        ];
    }

    /**
     * @throws ClientFilesCountExceededException
     */
    public function addFile(ClientFile $file): self
    {
        $allowedFilesCount = self::ALLOWED_FILES_COUNT;
        if ($this->files->count() >= $allowedFilesCount) {
            throw new ClientFilesCountExceededException("Client cannot have more than $allowedFilesCount files");
        }

        $this->files->set($file->id, $file);

        return $this;
    }

    /**
     * @return array<int, ClientFile>
     */
    public function getFiles(): array
    {
        return $this->files->toArray();
    }

    public function updateFile(ClientFile $clientFile): self
    {
        $file = $this->files->get($clientFile->id);
        $file->update($clientFile);

        return $this;
    }

    public function getFile(string $fileId): ?ClientFile
    {
        return $this->files->get($fileId);
    }

    private function validate(): array
    {
        $errors = [];

        if (empty($this->id)) {
            $errors['id'] = 'Id cannot be empty';
        }
        if (strlen($this->id) > 50) {
            $errors['id'] = 'Id cannot be longer than 50 characters';
        }

        if (empty($this->firstname)) {
            $errors['firstname'] = 'Firstname cannot be empty';
        }
        if (strlen($this->firstname) > 80) {
            $errors['firstname'] = 'Firstname cannot be longer than 80 characters';
        }

        if (empty($this->lastname)) {
            $errors['lastname'] = 'Lastname cannot be empty';
        }
        if (strlen($this->lastname) > 80) {
            $errors['lastname'] = 'Lastname cannot be longer than 80 characters';
        }

        return $errors;
    }
}
