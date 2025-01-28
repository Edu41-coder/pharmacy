<?php

namespace Core\View\TwigExtensions;

use Core\Session\FlashMessage;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class FlashExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('flash_messages', [$this, 'getFlashMessages']),
            new TwigFunction('has_flash', [$this, 'hasFlash']),
        ];
    }

    public function getFlashMessages(): array
    {
        return FlashMessage::getAll();
    }

    public function hasFlash(?string $type = null): bool
    {
        return FlashMessage::has($type);
    }
} 