<?php

namespace App\DependencyInjection\ChoicesSuite;

use App\DependencyInjection\ChoicesSuiteInterface;

class FibonacciChoicesSuite implements ChoicesSuiteInterface
{
    function getName(): string
    {
        return 'Fibonacci';
    }

    /**
     * @inheritDoc
     */
    function getValues(): array
    {
        return [
            '0',
            '1',
            '2',
            '3',
            '5',
            '8',
            '13',
            '21',
            '34',
            '55',
            '89',
            '?',
        ];
    }
}