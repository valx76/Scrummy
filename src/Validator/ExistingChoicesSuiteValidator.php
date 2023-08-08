<?php

namespace App\Validator;

use App\Exception\ChoicesSuiteNotFoundException;
use App\Service\ChoicesSuiteManager;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class ExistingChoicesSuiteValidator extends ConstraintValidator
{
    public function __construct(
        private readonly ChoicesSuiteManager $choicesSuiteManager,
    )
    {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof ExistingChoicesSuite) {
            throw new UnexpectedTypeException($constraint, ExistingChoicesSuite::class);
        }

        if ($value === null || $value === '') {
            return;
        }

        try {
            $this->choicesSuiteManager->getSuiteByName($value);
        } catch (ChoicesSuiteNotFoundException) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ suite }}', $value)
                ->addViolation();
        }
    }
}
