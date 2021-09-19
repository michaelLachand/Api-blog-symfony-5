<?php

namespace App\Entity;

use App\Entity\base\TraitEntity;
use App\Repository\TCommentRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TCommentRepository::class)
 * @ORM\Table(name="t_comment")
 */
class TComment
{
    use TraitEntity;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=1000)
     */
    private ?string $comment = null;

    /**
     * @ORM\ManyToOne(targetEntity=TUser::class, inversedBy="tComments")
     */
    private ?TUser $fk_user = null;

    /**
     * @ORM\ManyToOne(targetEntity=TArticle::class, inversedBy="tComments")
     */
    private ?TArticle $fk_article = null;

    public function tojson(): array
    {
        return [
            'date_save' => $this->date_save ? $this->date_save->format('c') : null,
            'active' => $this->active,
            'id' => $this->id,
            'comment' => $this->comment,
            'fk_user' => $this->fk_user ? $this->fk_user->tojson() : null,
            'fk_article' => $this->fk_article ? $this->fk_article->tojson() : null,
        ];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getFkUser(): ?TUser
    {
        return $this->fk_user;
    }

    public function setFkUser(?TUser $fk_user): self
    {
        $this->fk_user = $fk_user;

        return $this;
    }

    public function getFkArticle(): ?TArticle
    {
        return $this->fk_article;
    }

    public function setFkArticle(?TArticle $fk_article): self
    {
        $this->fk_article = $fk_article;

        return $this;
    }
}
