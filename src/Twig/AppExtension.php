<?php

namespace App\Twig;

use Symfony\Component\Asset\Packages;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function __construct(
        private UrlGeneratorInterface $urlGenerator,
        private Security $security,
        private Packages $assetPackages
    ) {}

    public function getFunctions(): array
    {
        return [
            new TwigFunction('is_active', [$this, 'isActive']),
            new TwigFunction('asset', [$this->assetPackages, 'getUrl']),
            new TwigFunction('is_granted', [$this->security, 'isGranted']),
            new TwigFunction('route', [$this, 'generateRoute']),
            new TwigFunction('route_exists', [$this, 'routeExists']),
            new TwigFunction('greeting', [$this, 'getGreeting']),
        ];
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('date_format', [$this, 'formatDate']),
            new TwigFilter('selected', [$this, 'selected']),
        ];
    }

    public function isActive(string $route): bool
    {
        return $this->urlGenerator->getContext()->getPathInfo() === $route;
    }

    public function formatDate($date, string $format = 'd/m/Y'): string
    {
        if ($date instanceof \DateTime) {
            return $date->format($format);
        }
        return (new \DateTime($date))->format($format);
    }

    public function selected($value, $current): string
    {
        return $value === $current ? ' selected' : '';
    }

    public function generateRoute(string $name, array $parameters = []): string
    {
        return $this->urlGenerator->generate($name, $parameters);
    }

    public function routeExists(string $name): bool
    {
        try {
            $this->urlGenerator->generate($name);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getGreeting(): string
    {
        $hour = (int) date('H');
        if ($hour >= 6 && $hour < 18) {
            return "Bonjour";
        } elseif ($hour >= 18 && $hour < 22) {
            return "Bonsoir";
        } else {
            return "Bonne nuit";
        }
    }
} 