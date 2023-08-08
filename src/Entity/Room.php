<?php

namespace App\Entity;

use App\Repository\RoomRepository;
use App\Validator\ExistingChoicesSuite;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation\Timestampable;

#[ORM\Entity(repositoryClass: RoomRepository::class)]
class Room
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[ExistingChoicesSuite]
    private ?string $choicesSuite = null;

    #[Timestampable(on: 'create')]
    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\OneToMany(mappedBy: 'room', targetEntity: RoomChoice::class)]
    private Collection $choices;

    #[ORM\Column]
    private bool $isClosed = false;

    public function __construct()
    {
        $this->choices = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getChoicesSuite(): ?string
    {
        return $this->choicesSuite;
    }

    public function setChoicesSuite(?string $choicesSuite): static
    {
        $this->choicesSuite = $choicesSuite;

        return $this;
    }

    /**
     * @return Collection<int, RoomChoice>
     */
    public function getChoices(): Collection
    {
        return $this->choices;
    }

    public function addChoice(RoomChoice $choice): static
    {
        if (!$this->choices->contains($choice)) {
            $this->choices->add($choice);
            $choice->setRoom($this);
        }

        return $this;
    }

    public function removeChoice(RoomChoice $choice): static
    {
        if ($this->choices->removeElement($choice)) {
            // set the owning side to null (unless already changed)
            if ($choice->getRoom() === $this) {
                $choice->setRoom(null);
            }
        }

        return $this;
    }

    public function isClosed(): bool
    {
        return $this->isClosed;
    }

    public function setIsClosed(bool $isClosed): static
    {
        $this->isClosed = $isClosed;

        return $this;
    }
}
