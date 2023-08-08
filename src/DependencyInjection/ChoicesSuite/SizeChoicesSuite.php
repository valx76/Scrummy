<?php

namespace App\DependencyInjection\ChoicesSuite;

use App\DependencyInjection\ChoicesSuiteInterface;

class SizeChoicesSuite implements ChoicesSuiteInterface
{
    function getName(): string
    {
        return 'Size';
    }

    /**
     * @inheritDoc
     */
    function getValues(): array
    {
        return [
            'XXS',
            'XS',
            'S',
            'M',
            'L',
            'XL',
            'XXL',
            '?',
        ];
    }
}