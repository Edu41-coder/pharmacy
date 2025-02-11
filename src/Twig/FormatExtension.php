<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class FormatExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('price', [$this, 'formatPrice']),
            new TwigFilter('date_fr', [$this, 'formatDateFr']),
        ];
    }

    public function formatPrice(float $number): string
    {
        return number_format($number, 2, ',', ' ') . ' €';
    }

    public function formatDateFr(\DateTime $date): string
    {
        return $date->format('d/m/Y');
    }
} 