<?php

namespace Core\Router;

interface RouterInterface
{
    public function get(string $path, $callable, ?string $name = null): Route;
    public function post(string $path, $callable, ?string $name = null): Route;
    public function put(string $path, $callable, ?string $name = null): Route;
    public function delete(string $path, $callable, ?string $name = null): Route;
    public function options(string $path, $callable, ?string $name = null): Route;
    public function any(string $path, $callable, ?string $name = null): Route;
    public function group(string $prefix, callable $callback): self;
    public function middleware($middleware, array $params = []): self;
    public function run();
    public function url(string $name, array $params = []): string;
    public function getRoutes(): array;
    public function getNamedRoutes(): array;
    public function hasNamedRoute(string $name): bool;
    public function getCurrentUrl(): string;
    public function getPrefix(): string;
} 