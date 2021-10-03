<?php

namespace App\Entity;

use App\Entity\base\TraitEntity;
use App\Repository\TArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=TArticleRepository::class)
 * @ORM\Table(name="t_article")
 */
class TArticle
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
    private ?string $title = null;

    /**
     * @ORM\Column(type="string", length=3000)
     */
    private ?string $description = null;

    /**
     * @ORM\ManyToOne(targetEntity=TUser::class, inversedBy="tArticles")
     */
    private ?TUser $fk_user = null;

    /**
     * @ORM\OneToMany(targetEntity=TComment::class, mappedBy="fk_article")
     */
    private Collection $tComments;

    /**
     * @ORM\ManyToOne(targetEntity=TCategorie::class, inversedBy="tArticles")
     */
    private ?TCategorie $fk_categories = null;

    public function __construct()
    {
        $this->tComments = new ArrayCollection();
    }

    public function tojson(): array
    {
        return [
            'date_save' => $this->date_save ? $this->date_save->format('c') : null,
            'active' => $this->active,
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'fk_user' => $this->fk_user ? $this->fk_user->tojson() : null,
            'fk_categorie' => $this->fk_categories ? $this->fk_categories->tojson() : null,
        ];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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

    public function getFkUser(): ?TUser
    {
        return $this->fk_user;
    }

    /**
     * @param TUser|null|UserInterface $fk_user
     * @return $this
     */
    public function setFkUser(?TUser $fk_user): self
    {
        $this->fk_user = $fk_user;

        return $this;
    }

    /**
     * @return Collection|TComment[]
     */
    public function getTComments(): Collection
    {
        return $this->tComments;
    }

    public function addTComment(TComment $tComment): self
    {
        if (!$this->tComments->contains($tComment)) {
            $this->tComments[] = $tComment;
            $tComment->setFkArticle($this);
        }

        return $this;
    }

    public function removeTComment(TComment $tComment): self
    {
        if ($this->tComments->removeElement($tComment)) {
            // set the owning side to null (unless already changed)
            if ($tComment->getFkArticle() === $this) {
                $tComment->setFkArticle(null);
            }
        }

        return $this;
    }

    public function getFkCategories(): ?TCategorie
    {
        return $this->fk_categories;
    }

    public function setFkCategories(?TCategorie $fk_categories): self
    {
        $this->fk_categories = $fk_categories;

        return $this;
    }
}
