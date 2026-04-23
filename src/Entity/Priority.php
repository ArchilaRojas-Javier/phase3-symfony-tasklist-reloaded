<?php

namespace App\Entity;

use App\Repository\PriorityRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PriorityRepository::class)]
class Priority
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $importance = null;

    #[ORM\OneToOne(mappedBy: 'priority', cascade: ['persist', 'remove'])]
    private ?Task $task = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getImportance(): ?int
    {
        return $this->importance;
    }

    public function setImportance(int $importance): static
    {
        $this->importance = $importance;

        return $this;
    }

    public function getTask(): ?Task
    {
        return $this->task;
    }

    public function setTask(?Task $task): static
    {
        // unset the owning side of the relation if necessary
        if ($task === null && $this->task !== null) {
            $this->task->setPriority(null);
        }

        // set the owning side of the relation if necessary
        if ($task !== null && $task->getPriority() !== $this) {
            $task->setPriority($this);
        }

        $this->task = $task;

        return $this;
    }
}
