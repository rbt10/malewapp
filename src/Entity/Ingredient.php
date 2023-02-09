<?php

namespace App\Entity;

use App\Repository\IngredientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: IngredientRepository::class)]
class Ingredient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $ingredienNom;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIngredienNom(): ?string
    {
        return $this->ingredienNom;
    }

    public function setIngredienNom(string $ingredienNom): self
    {
        $this->ingredienNom = $ingredienNom;

        return $this;
    }

    // conversion en chaine de caractères
    public function __toString(){
        return $this->getIngredienNom();
    }

}
