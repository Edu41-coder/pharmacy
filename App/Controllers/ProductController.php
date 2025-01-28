<?php

class ProductController
{
    private ProductService $productService;

    public function __construct(Container $container)
    {
        // Le container résout automatiquement ProductService et ses dépendances
        $this->productService = $container->get(ProductService::class);
    }

    public function index()
    {
        $products = $this->productService->getAllProducts();
        // ...
    }
} 