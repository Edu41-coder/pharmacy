<?php

namespace Core\Controller;

abstract class BaseController
{
    protected string $viewPath;
    protected array $sharedViewData = [];
    protected ?string $layout = 'default';

    public function __construct()
    {
        // Initialize shared view data
        $this->sharedViewData = [
            'site_title' => 'Gestion Pharmacie',
            'user' => $this->getUser()
        ];

        $this->viewPath = dirname(__DIR__, 2) . '/App/views';
        $this->initialize();
    }

    public function initialize(): void
    {
        $this->viewPath = dirname(__DIR__, 2) . '/App/views';
    }

    protected function render(string $view, array $data = []): string
    {
        // Merge shared data with specific view data
        $viewData = array_merge($this->sharedViewData, $data);
        
        // Extract data to make it available in the view
        extract($viewData);
        
        // Start output buffering
        ob_start();
        
        // Include the view file
        $viewFile = $this->viewPath . '/' . $view . '.php';
        if (!file_exists($viewFile)) {
            throw new \RuntimeException("View file not found: {$viewFile}");
        }
        require $viewFile;
        
        $content = ob_get_clean();
        
        // If layout is set, include it
        if ($this->layout !== null) {
            $layoutFile = $this->viewPath . '/layouts/' . $this->layout . '.php';
            if (file_exists($layoutFile)) {
                ob_start();
                require $layoutFile;
                return ob_get_clean();
            }
        }
        
        return $content;
    }

    protected function redirect(string $path): string
    {
        header('Location: ' . $path);
        exit();
    }

    protected function getUser(): ?array
    {
        return $_SESSION['user'] ?? null;
    }

    protected function isAuthenticated(): bool
    {
        return isset($_SESSION['user']);
    }

    protected function isAdmin(): bool
    {
        return isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin';
    }

    protected function requireAuth(): void
    {
        if (!$this->isAuthenticated()) {
            $this->redirect('/login');
        }
    }

    protected function requireAdmin(): void
    {
        if (!$this->isAdmin()) {
            $this->redirect('/');
        }
    }
}
