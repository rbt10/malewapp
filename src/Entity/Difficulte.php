<?php

namespace App\Entity;

use App\Repository\DifficulterecetteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DifficulterecetteRepository::class)]
class Difficulte
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $libelleDifficulte;

    #[ORM\OneToMany(mappedBy: 'difficulte', targetEntity: Recette::class)]
    private $recettes;

    public function __construct()
    {
        $this->recettes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelleDifficulte(): ?string
    {
        return $this->libelleDifficulte;
    }

    public function setLibelleDifficulte(string $libelleDifficulte): self
    {
        $this->libelleDifficulte = $libelleDifficulte;

        return $this;
    }


    public function __toString()
    {
        return $this->libelleDifficulte;
    }

    /**
     * @return Collection<int, Recette>
     */
    public function getRecettes(): Collection
    {
        return $this->recettes;
    }

    public function addRecette(Recette $recette): self
    {
        if (!$this->recettes->contains($recette)) {
            $this->recettes[] = $recette;
            $recette->setDifficulte($this);
        }

        return $this;
    }

    public function removeRecette(Recette $recette): self
    {
        if ($this->recettes->removeElement($recette)) {
            // set the owning side to null (unless already changed)
            if ($recette->getDifficulte() === $this) {
                $recette->setDifficulte(null);
            }
        }

        return $this;
    }
}
