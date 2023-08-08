<?php

namespace App\DependencyInjection\ChoicesSuite;

use App\DependencyInjection\ChoicesSuiteInterface;

class SequentialChoicesSuite implements ChoicesSuiteInterface
{
    function getName(): string
    {
        return 'Sequential';
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
            '4',
            '5',
            '6',
            '7',
            '8',
            '9',
            '10',
            '?',
        ];
    }
}