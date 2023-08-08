<?php

namespace App\DependencyInjection\ChoicesSuite;

use App\DependencyInjection\ChoicesSuiteInterface;

class ScrumChoicesSuite implements ChoicesSuiteInterface
{
    function getName(): string
    {
        return 'Scrum';
    }

    /**
     * @inheritDoc
     */
    function getValues(): array
    {
        return [
            '0',
            '½',
            '1',
            '2',
            '3',
            '5',
            '8',
            '13',
            '20',
            '40',
            '100',
            '?',
            '☕',
        ];
    }
}