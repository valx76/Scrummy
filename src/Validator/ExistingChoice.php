<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class ExistingChoice extends Constraint
{
    public string $message = 'The choice "{{ choice }}" does not exist.';
}
