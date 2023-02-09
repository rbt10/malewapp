<?php

namespace App\Entity;

use App\Repository\NotesRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: NotesRepository::class)]
#[UniqueEntity(
    fields: ['user', 'recette'],
    message: 'Cet utilisateur a déjà noté cette recette',
    errorPath: 'user'
)]
class Notes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    #[ORM\Column(type: 'integer')]
    #[Assert\Positive()]
    #[Assert\LessThan(6)]
    private ?int $note;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'notes')]
    private User $user;

    #[ORM\ManyToOne(targetEntity: Recette::class, inversedBy: 'notes')]
    #[ORM\JoinColumn(nullable: false)]
    private Recette $recette;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeImmutable $dateCreation;


    public function __construct()
    {
        $this->dateCreation = new \DateTimeImmutable();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(int $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getRecette(): ?Recette
    {
        return $this->recette;
    }

    public function setRecette(?Recette $recette): self
    {
        $this->recette = $recette;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }
}
