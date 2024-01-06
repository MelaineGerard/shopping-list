<?php

declare(strict_types=1);

namespace App\Twig\Extension;

use App\Twig\Runtime\FileExtensionRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class FileExtension extends AbstractExtension
{
    #[\Override]
    public function getFunctions(): array
    {
        return [
            new TwigFunction('getFileUrl', [FileExtensionRuntime::class, 'getFileUrl']),
        ];
    }
}
