<?php

namespace App\DependencyInjection;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('app.choices_suite')]
interface ChoicesSuiteInterface
{
    function getName(): string;

    /**
     * @return string[]
     */
    function getValues(): array;
}
