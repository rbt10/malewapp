<?php

namespace App\Entity;

use App\Repository\SpecialiteProvinceRepository;
use Cocur\Slugify\Slugify;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: SpecialiteProvinceRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[UniqueEntity('slug', message: 'ce slug existe déjà')]
class SpecialiteProvince
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $libelle;

    #[ORM\Column(type: 'string',length: 255, unique: true)]
    #[Assert\NotBlank()]
    private ?string $slug = null;

    #[ORM\OneToMany(mappedBy: 'province', targetEntity: Recette::class)]
    private $recettes;

    #[ORM\OneToMany(mappedBy: 'province', targetEntity: Territoire::class)]
    private $territoires;

    public function __construct()
    {
        $this->recettes = new ArrayCollection();
        $this->territoires = new ArrayCollection();
    }
    #[ORM\PrePersist]
    public function prePersist(){
        $this->slug = (new Slugify())->slugify($this->libelle);
    }

    public function __toString()
    {
        return $this->libelle;
    }
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
            $recette->setProvince($this);
        }

        return $this;
    }

    public function removeRecette(Recette $recette): self
    {
        if ($this->recettes->removeElement($recette)) {
            // set the owning side to null (unless already changed)
            if ($recette->getProvince() === $this) {
                $recette->setProvince(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Territoire>
     */
    public function getTerritoires(): Collection
    {
        return $this->territoires;
    }

    public function addTerritoire(Territoire $territoire): self
    {
        if (!$this->territoires->contains($territoire)) {
            $this->territoires[] = $territoire;
            $territoire->setProvince($this);
        }

        return $this;
    }

    public function removeTerritoire(Territoire $territoire): self
    {
        if ($this->territoires->removeElement($territoire)) {
            // set the owning side to null (unless already changed)
            if ($territoire->getProvince() === $this) {
                $territoire->setProvince(null);
            }
        }

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * @param string|null $slug
     */
    public function setSlug(?string $slug): void
    {
        $this->slug = $slug;
    }

}
