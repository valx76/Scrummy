<?php

namespace App\Service;

use App\DependencyInjection\ChoicesSuiteInterface;
use App\Exception\ChoicesSuiteNotFoundException;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;

readonly class ChoicesSuiteManager
{
    /**
     * @var ChoicesSuiteInterface[]
     */
    private array $suites;

    /**
     * @param ChoicesSuiteInterface[] $suites
     */
    public function __construct(
        #[TaggedIterator('app.choices_suite')] iterable $suites,
    )
    {
        $this->suites = $suites instanceof \Traversable ? iterator_to_array($suites) : $suites;
    }

    /**
     * @return ChoicesSuiteInterface[]
     */
    public function getSuites(): array
    {
        return $this->suites;
    }

    /**
     * @throws ChoicesSuiteNotFoundException
     */
    public function getSuiteByName(string $name): ChoicesSuiteInterface
    {
        foreach ($this->suites as $suite) {
            if (strcmp($suite->getName(), $name) === 0) {
                return $suite;
            }
        }

        throw new ChoicesSuiteNotFoundException();
    }
}
