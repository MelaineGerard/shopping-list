<?php

declare(strict_types=1);

namespace App\Twig\Runtime;

use League\Flysystem\FilesystemOperator;
use Twig\Extension\RuntimeExtensionInterface;

readonly class FileExtensionRuntime implements RuntimeExtensionInterface
{
    public function __construct(
        private FilesystemOperator $defaultStorage,
    ) {}

    public function getFileUrl(string $filename): string
    {
        return $this->defaultStorage->publicUrl($filename);
    }
}
