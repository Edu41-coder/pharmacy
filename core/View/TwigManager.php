<?php

namespace Core\View;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Core\View\TwigExtensions\FlashExtension;
use Core\View\TwigExtensions\AppExtension;

class TwigManager
{
    private static ?Environment $instance = null;
    private Environment $twig;

    private function __construct()
    {
        $loader = new FilesystemLoader(dirname(__DIR__, 2) . '/App/views');
        
        $this->twig = new Environment($loader, [
            'cache' => $_ENV['APP_ENV'] === 'production' ? '../var/cache/twig' : false,
            'debug' => $_ENV['APP_ENV'] === 'development',
            'auto_reload' => true
        ]);

        $this->addExtensions();
        $this->addGlobals();
    }

    /**
     * Empêche le clonage de l'instance
     */
    private function __clone() {}

    /**
     * Récupère l'instance unique de Twig
     */
    public static function getInstance(): Environment
    {
        if (self::$instance === null) {
            $manager = new self();
            self::$instance = $manager->twig;
        }

        return self::$instance;
    }

    /**
     * Ajoute les extensions personnalisées à Twig
     */
    private function addExtensions(): void
    {
        // Ajouter l'extension Flash
        $this->twig->addExtension(new FlashExtension());
        
        // Ajouter l'extension App
        $this->twig->addExtension(new AppExtension());

        // Ajouter la fonction is_granted
        $this->twig->addFunction(new \Twig\TwigFunction('is_granted', function($role) {
            return isset($_SESSION['user']) && $_SESSION['user']['role'] === $role;
        }));

        // Ajouter d'autres extensions si nécessaire...
    }

    private function addGlobals(): void
    {
        $this->twig->addGlobal('app_name', $_ENV['APP_NAME']);
        $this->twig->addGlobal('user', $_SESSION['user'] ?? null);
    }

    /**
     * Récupère l'instance de Twig
     */
    public function getTwig(): Environment
    {
        return $this->twig;
    }
} 