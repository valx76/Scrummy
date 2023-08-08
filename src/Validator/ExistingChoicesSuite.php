<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class ExistingChoicesSuite extends Constraint
{
    public string $message = 'The suite "{{ suite }}" does not exist.';
}
