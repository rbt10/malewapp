<?php

namespace App\Entity;

use App\Repository\RecetteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;


#[Vich\Uploadable]
#[UniqueEntity('name')]
#[ORM\Entity(repositoryClass: RecetteRepository::class)]
class Recette
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    #[Assert\NotBlank()]
    #[Assert\Length(min: 2, max: 50)]
    #[ORM\Column(type: 'string', length: 255)]
    private ?string $name;

    #[ORM\ManyToOne(targetEntity: Difficulte::class, inversedBy: 'recettes')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Difficulte $difficulte;

    #[ORM\ManyToOne(targetEntity: Categorie::class, inversedBy: 'recettes')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Categorie $category;

    #[Assert\NotBlank()]
    #[ORM\Column(type: 'text')]
    private ?string $description;


    #[Assert\Positive()]
    #[Assert\LessThan(11)]
    #[ORM\Column(type: 'float', nullable: true)]
    private ?int $price;

    #[ORM\ManyToMany(targetEntity: Ingredient::class)]
    private $ingredients;

    #[ORM\Column(type: 'time')]
    private ?\DateTimeInterface $duree;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'recette')]
    private ?User $user;

    // NOTE: This is not a mapped field of entity metadata, just a simple property.
    #[Vich\UploadableField(mapping: 'recette_images', fileNameProperty: 'image')]
    private ?File $imageFile = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $image = "" ;

    #[Gedmo\Slug(fields: ['name'])]
    #[ORM\Column(type: 'string', length: 255)]
    private ?string $slug;

    #[ORM\Column(type: 'boolean')]
    private ?bool $isPublic;

    #[ORM\Column(type: 'boolean')]
    private ?bool $isBest = false;

    #[ORM\ManyToOne(targetEntity: SpecialiteProvince::class, inversedBy: 'recettes')]
    private ?SpecialiteProvince $province;

    #[ORM\OneToMany(mappedBy: 'recette', targetEntity: Favoris::class)]
    private $favorite;

    #[ORM\OneToMany(mappedBy: 'recette', targetEntity: Commentaire::class, orphanRemoval: true)]
    private $commentaires;

    #[ORM\OneToMany(mappedBy: 'recette', targetEntity: Notes::class)]
    private $notes;

    private ?float $moyenne = null;

    #[ORM\Column(type: 'datetime_immutable', options: ['default' =>'CURRENT_TIMESTAMP'])]
    private ?\DateTimeImmutable $updatedAt;

    #[Vich\UploadableField(mapping: 'recette_videos', fileNameProperty: 'videos')]
    private ?File $videoFile = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $videos ="";

    public function __construct()
    {
        $this->ingredients = new ArrayCollection();

        $this->favorite = new ArrayCollection();
        $this->commentaires = new ArrayCollection();
        $this->notes = new ArrayCollection();

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }


    public function getDifficulte(): ?Difficulte
    {
        return $this->difficulte;
    }

    public function setDifficulte(?Difficulte $difficulte): self
    {
        $this->difficulte = $difficulte;

        return $this;
    }

    public function getCategory(): ?Categorie
    {
        return $this->category;
    }

    public function setCategory(?Categorie $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): self
    {
        $this->price = $price;

        return $this;
    }


    /**
     * @return Collection<int, Ingredient>
     */
    public function getIngredients(): Collection
    {
        return $this->ingredients;
    }

    public function addIngredient(Ingredient $ingredient): self
    {
        if (!$this->ingredients->contains($ingredient)) {
            $this->ingredients[] = $ingredient;
        }

        return $this;
    }

    public function removeIngredient(Ingredient $ingredient): self
    {
        $this->ingredients->removeElement($ingredient);

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @return File|null
     */
    public function getVideoFile(): ?File
    {
        return $this->videoFile;
    }

    /**
     * @param File|null $videoFile
     */
    public function setVideoFile(?File $video = null): void
    {
        $this->videoFile = $video;



        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if ($video) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->setUpdatedAt(new \DateTimeImmutable('now'));
        }
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }


    public function setImageFile(?File $image = null)
    {
        $this->imageFile = $image;

        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if ($image) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->setUpdatedAt(new \DateTimeImmutable('now'));
        }
    }

    public function getImageFile()
    {
        return $this->imageFile;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug($slug1): self
    {
        $this->slug = $slug1;

        return $this;
    }


    public function isIsBest(): ?bool
    {
        return $this->isBest;
    }

    public function setIsBest(bool $isBest): self
    {
        $this->isBest = $isBest;

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

    public function getDuree(): ?\DateTimeInterface
    {
        return $this->duree;
    }

    public function setDuree(\DateTimeInterface $duree): self
    {
        $this->duree = $duree;

        return $this;
    }

    /**
     * @return Collection<int, Favoris>
     */
    public function getFavorite(): Collection
    {
        return $this->favorite;
    }

    public function addFavorite(Favoris $favorite): self
    {
        if (!$this->favorite->contains($favorite)) {
            $this->favorite[] = $favorite;
            $favorite->setRecette($this);
        }

        return $this;
    }

    public function removeFavorite(Favoris $favorite): self
    {
        if ($this->favorite->removeElement($favorite)) {
            // set the owning side to null (unless already changed)
            if ($favorite->getRecette() === $this) {
                $favorite->setRecette(null);
            }
        }

        return $this;
    }


    /**
     * Permet de savoir si cet article est aimé par un utilisateur
     * @param User $user
     * @return bool
     */
    public function islikedByUser(User $user): bool
    {
        foreach ($this->favorite as $like){
            if ($like->getUser()=== $user ){
                return true;
            }
            else{
                return false;
            }

        }
        return false;

    }

    public function isIsPublic(): ?bool
    {
        return $this->isPublic;
    }

    public function setIsPublic(bool $isPublic): self
    {
        $this->isPublic = $isPublic;

        return $this;
    }

    /**
     * @return Collection<int, Commentaire>
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaire $commentaire): self
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires[] = $commentaire;
            $commentaire->setRecette($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): self
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getRecette() === $this) {
                $commentaire->setRecette(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Notes>
     */
    public function getNotes(): Collection
    {
        return $this->notes;
    }

    public function addNote(Notes $note): self
    {
        if (!$this->notes->contains($note)) {
            $this->notes[] = $note;
            $note->setRecette($this);
        }

        return $this;
    }

    public function removeNote(Notes $note): self
    {
        if ($this->notes->removeElement($note)) {
            // set the owning side to null (unless already changed)
            if ($note->getRecette() === $this) {
                $note->setRecette(null);
            }
        }

        return $this;
    }

    /**
     * @return float|null
     */
    public function getMoyenne(): ?float
    {
        $notes = $this->notes;

        if ($notes->toArray() === []){
            $this->moyenne = null;
            return $this->moyenne;
        }

        $total = 0;
        foreach ($notes as $note){
            $total +=$note->getNotes();
        }
        $this->moyenne = $total /count($notes);
        return $this->moyenne;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getVideos(): ?string
    {
        return $this->videos;
    }

    public function setVideos(?string $videos): self
    {
        $this->videos = $videos;

        return $this;
    }


}
