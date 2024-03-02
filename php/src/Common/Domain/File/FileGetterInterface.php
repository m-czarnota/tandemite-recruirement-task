<?php

namespace App\Common\Domain\File;

use SplFileInfo;

interface FileGetterInterface
{
    /**
     * @param string $filePath
     * @return SplFileInfo
     * @throws FileNotExistException
     */
    public function execute(string $filePath): SplFileInfo;
}