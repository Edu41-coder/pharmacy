<?php

namespace Core\View\TwigExtensions;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('isActive', [$this, 'isActive']),
            new TwigFunction('asset', [$this, 'asset']),
            new TwigFunction('csrf_token', [$this, 'csrfToken']),
            new TwigFunction('csrf', [$this, 'csrfField'], ['is_safe' => ['html']]),
            new TwigFunction('url', 'url'),
            new TwigFunction('session', 'session')
        ];
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('date_format', [$this, 'formatDate']),
            new TwigFilter('selected', [$this, 'selected']),
            new TwigFilter('checked', [$this, 'checked'])
        ];
    }

    public function isActive(string $path): bool
    {
        $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        return strpos($currentPath, $path) === 0;
    }

    public function asset(string $path): string
    {
        return '/assets/' . ltrim($path, '/');
    }

    public function formatDate($date, string $format = 'd/m/Y H:i'): string
    {
        return (new \DateTime($date))->format($format);
    }

    public function selected($value, $current): string
    {
        return $value === $current ? ' selected' : '';
    }

    public function checked($value): string
    {
        return $value ? ' checked' : '';
    }
} 