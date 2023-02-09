<?php

namespace App\Entity;

use App\Repository\TerritoireRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TerritoireRepository::class)]
class Territoire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $libelle;

    #[ORM\ManyToOne(targetEntity: SpecialiteProvince::class, inversedBy: 'territoires')]
    private $province;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getProvince(): ?SpecialiteProvince
    {
        return $this->province;
    }

    public function setProvince(?SpecialiteProvince $province): self
    {
        $this->province = $province;

        return $this;
    }
}
