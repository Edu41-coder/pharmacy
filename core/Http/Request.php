<?php

namespace Core\Http;

class Request
{
    private array $get;
    private array $post;
    private array $server;
    private array $files;
    private array $cookies;
    private ?string $rawBody;
    private array $headers;

    public function __construct()
    {
        $this->get = $_GET;
        $this->post = $_POST;
        $this->server = $_SERVER;
        $this->files = $_FILES;
        $this->cookies = $_COOKIE;
        $this->rawBody = file_get_contents('php://input');
        $this->headers = getallheaders();
    }

    public function getMethod(): string
    {
        return strtoupper($this->server['REQUEST_METHOD']);
    }

    public function get(string $key, $default = null)
    {
        return $_GET[$key] ?? $_POST[$key] ?? $default;
    }

    public function post(string $key = null, $default = null)
    {
        if ($key === null) {
            return $this->post;
        }
        return $this->post[$key] ?? $default;
    }

    public function getJson(): array
    {
        if ($this->isJson()) {
            $data = json_decode($this->rawBody, true);
            return $data !== null ? $data : [];
        }
        return [];
    }

    public function isJson(): bool
    {
        return str_contains($this->getHeader('Content-Type') ?? '', 'application/json');
    }

    public function getHeader(string $name): ?string
    {
        return $this->headers[$name] ?? null;
    }

    public function wantsJson(): bool
    {
        return $this->isJson() || $this->isAjax();
    }

    public function isAjax(): bool
    {
        return ($this->getHeader('X-Requested-With') ?? '') === 'XMLHttpRequest';
    }

    public function getUri(): string
    {
        return $_SERVER['REQUEST_URI'] ?? '/';
    }
} 