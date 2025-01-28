<?php

namespace Tests\Core\Router;

use PHPUnit\Framework\TestCase;
use Core\Router\Router;

class RouterTest extends TestCase
{
    private Router $router;

    protected function setUp(): void
    {
        $_ENV['BASE_PATH'] = '/Pharmacie';
        $this->router = new Router();
    }

    /**
     * @dataProvider urlProvider
     */
    public function testParseUrl(string $input, string $expected): void
    {
        $router = new Router($input);
        $this->assertEquals($expected, $router->getCurrentUrl());
    }

    public function urlProvider(): array
    {
        return [
            'URL simple' => [
                '/products',
                'products'
            ],
            'URL avec query string' => [
                '/products?id=1&category=2',
                'products'
            ],
            'URL avec BASE_PATH' => [
                '/Pharmacie/products',
                'products'
            ],
            'URL avec slashes multiples' => [
                '/Pharmacie//products///list/',
                'products/list'
            ],
            'URL avec BASE_PATH et query string' => [
                '/Pharmacie/products/1?category=2',
                'products/1'
            ],
            'URL racine' => [
                '/Pharmacie/',
                ''
            ],
            'URL avec paramètres' => [
                '/Pharmacie/products/1/edit',
                'products/1/edit'
            ]
        ];
    }

    public function testMatchRoute(): void
    {
        $router = new Router('/Pharmacie/products');
        
        $router->get('/products', function() {
            return 'products list';
        });

        $this->assertTrue($router->getRoutes()['GET'][0]->match('products'));
    }

    public function testRouteWithParameters(): void
    {
        $router = new Router('/Pharmacie/products/1');
        
        $router->get('/products/{id}', function($id) {
            return "Product $id";
        });

        $route = $router->getRoutes()['GET'][0];
        $this->assertTrue($route->match('products/1'));
        $this->assertEquals(['id' => '1'], $route->getMatches());
    }
} 