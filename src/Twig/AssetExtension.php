<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AssetExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('asset_url', [$this, 'getAssetUrl']),
        ];
    }

    public function getAssetUrl(string $path): string
    {
        return '/build/' . ltrim($path, '/');
    }
} 