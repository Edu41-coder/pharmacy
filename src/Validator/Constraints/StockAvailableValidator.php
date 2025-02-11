<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class StockAvailableValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint): void
    {
        if ($value < 0) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
} 