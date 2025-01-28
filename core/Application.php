<?php

namespace Core;

use Core\Router\Router;
use Core\Container\Container;
use Core\View\TwigManager;

class Application
{
    private Container $container;
    private Router $router;
    private TwigManager $twig;
    
    public function __construct()
    {
        $this->initializeComponents();
    }

    private function initializeComponents(): void
    {
        // Uniquement les composants spécifiques
        $this->container = new Container();
        
        // Charge les services
        $services = require dirname(__DIR__) . '/config/services.php';
        foreach ($services as $id => $factory) {
            $this->container->set($id, $factory);
        }
        
        $this->router = new Router();
        $this->twig = TwigManager::getInstance();
    }

    public function run(): void
    {
        // Charger les routes
        require_once dirname(__DIR__) . '/routes/web.php';
        
        if ($this->isApiRequest()) {
            require_once dirname(__DIR__) . '/routes/api.php';
        }
        
        $this->router->run();
    }
} 