<?php

namespace App\Common\Domain\File;

interface FileUploaderInterface
{
    /**
     * @param string $filePath
     * @return string
     * @throws FileNotExistException
     */
    public function execute(string $filePath): string;
}