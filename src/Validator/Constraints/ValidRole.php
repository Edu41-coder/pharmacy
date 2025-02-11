<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class ValidRole extends Constraint
{
    public string $message = 'Le rôle "{{ value }}" n\'est pas valide.';
} 