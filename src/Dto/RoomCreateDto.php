<?php

namespace App\Dto;

use App\Validator\ExistingChoicesSuite;
use Symfony\Component\Validator\Constraints\NotBlank;

final readonly class RoomCreateDto
{
    public function __construct(
        public ?string $name,
        #[NotBlank]
        #[ExistingChoicesSuite]
        public string $choicesSuite,
    )
    {
    }
}
