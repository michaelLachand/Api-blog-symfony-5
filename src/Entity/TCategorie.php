<?php

namespace App\Entity;

use App\Entity\base\TraitEntity;
use App\Repository\TCategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TCategorieRepository::class)
 * @ORM\Table(name="t_categorie")
 */
class TCategorie
{
    use TraitEntity;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $titre = null;

    /**
     * @ORM\Column(type="string", length=1000, nullable=true)
     */
    private ?string $description = null;

    /**
     * @ORM\OneToMany(targetEntity=TArticle::class, mappedBy="fk_categories")
     */
    private Collection $tArticles;

    public function __construct()
    {
        $this->tArticles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|TArticle[]
     */
    public function getTArticles(): Collection
    {
        return $this->tArticles;
    }

    public function addTArticle(TArticle $tArticle): self
    {
        if (!$this->tArticles->contains($tArticle)) {
            $this->tArticles[] = $tArticle;
            $tArticle->setFkCategories($this);
        }

        return $this;
    }

    public function removeTArticle(TArticle $tArticle): self
    {
        if ($this->tArticles->removeElement($tArticle)) {
            // set the owning side to null (unless already changed)
            if ($tArticle->getFkCategories() === $this) {
                $tArticle->setFkCategories(null);
            }
        }

        return $this;
    }
}
