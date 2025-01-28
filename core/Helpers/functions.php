<?php

if (!function_exists('url')) {
    function url(string $path = '', array $params = []): string
    {
        $basePath = $_ENV['BASE_PATH'] ?? '/Pharmacie';
        
        // Clean the path
        $path = trim($path, '/');
        
        // Construct final URL
        $finalPath = rtrim($basePath, '/');
        
        if (!empty($path)) {
            if (strpos($path, 'api/') !== 0) {
                $finalPath .= '/public/' . $path;
            } else {
                $finalPath .= '/' . $path;
            }
        } else {
            $finalPath .= '/public';
        }

        if (!empty($params)) {
            $finalPath .= '?' . http_build_query($params);
        }

        return $finalPath;
    }
}

if (!function_exists('asset')) {
    function asset(string $path): string
    {
        return url('assets/' . ltrim($path, '/'));
    }
}

if (!function_exists('redirect')) {
    function redirect(string $path): void
    {
        header('Location: ' . url($path));
        exit;
    }
}

if (!function_exists('session')) {
    function session(?string $key = null, $default = null)
    {
        if ($key === null) {
            return $_SESSION;
        }
        return $_SESSION[$key] ?? $default;
    }
} 