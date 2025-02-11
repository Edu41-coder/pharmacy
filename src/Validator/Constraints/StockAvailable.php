<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class StockAvailable extends Constraint
{
    public string $message = 'Le stock disponible doit être suffisant';
} 