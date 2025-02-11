<?php

namespace App\Exception;

use RuntimeException;

/**
 * Exception pour la logique métier spécifique à votre application
 */
class BusinessException extends RuntimeException
{
    public static function productOutOfStock(string $productName): self
    {
        return new self("Le produit '$productName' n'est plus en stock");
    }

    public static function insufficientQuantity(string $productName, int $requested, int $available): self
    {
        return new self(
            "Quantité insuffisante pour '$productName'. Demandé: $requested, Disponible: $available"
        );
    }
} 