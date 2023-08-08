<?php

namespace App\Validator;

use App\Entity\RoomChoice;
use App\Service\ChoicesSuiteManager;
use RuntimeException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class ExistingChoiceValidator extends ConstraintValidator
{
    public function __construct(
        private readonly ChoicesSuiteManager $choicesSuiteManager,
    )
    {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof ExistingChoice) {
            throw new UnexpectedTypeException($constraint, ExistingChoice::class);
        }

        if ($value === null || $value === '') {
            return;
        }

        $choices = $this->choicesSuiteManager
            ->getSuiteByName($this->getChoicesSuiteName())
            ->getValues();

        if (!in_array($value, $choices)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ choice }}', $value)
                ->addViolation();
        }
    }

    /**
     * @throws RuntimeException
     */
    private function getChoicesSuiteName(): string
    {
        $roomChoice = $this->context->getObject();

        if (!$roomChoice instanceof RoomChoice) {
            throw new RuntimeException(
                'The ExistingChoice constraint should be applied on the RoomChoice entity.'
            );
        }


        $choicesSuiteName = $roomChoice->getRoom()?->getChoicesSuite();

        if ($choicesSuiteName === null) {
            throw new RuntimeException(
                'The room needs to have a choices suite.'
            );
        }

        return $choicesSuiteName;
    }
}
