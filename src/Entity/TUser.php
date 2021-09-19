<?php

namespace App\Entity;

use App\Entity\base\TraitEntity;
use App\Repository\TUserRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Serializable;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=TUserRepository::class)
 * @ORM\Table(name="t_user")
 * @UniqueEntity(fields="username", message="username already used")
 * @method string getUserIdentifier()
 */
class TUser implements UserInterface, Serializable
{
    use TraitEntity;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private ?string $username = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $firstname = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $lastname = null;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private ?DateTimeInterface $naissance = null;

    /**
     * @ORM\ManyToOne(targetEntity=TPays::class, inversedBy="tUsers")
     */
    private ?TPays $fk_pays = null;

    /**
     * @ORM\OneToMany(targetEntity=TArticle::class, mappedBy="fk_user")
     */
    private Collection $tArticles;

    /**
     * @ORM\OneToMany(targetEntity=TComment::class, mappedBy="fk_user")
     */
    private Collection $tComments;

    /**
     * @ORM\Column(type="string", length=3000, nullable=true)
     */
    private ?string $password = null;

    /**
     * @ORM\Column(type="array")
     */
    private array $roles = [];

    public function __construct()
    {
        $this->date_save = new DateTime();
        $this->tArticles = new ArrayCollection();
        $this->tComments = new ArrayCollection();
    }

    public function tojson(): array
    {
        return [
            'date_save' => $this->date_save ? $this->date_save->format('c') : null,
            'active' => $this->active,
            'id' => $this->id,
            'username' => $this->username,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'naissance' => $this->naissance ? $this->naissance->format('c') : null,
            'roles' => $this->roles
        ];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getNaissance(): ?DateTimeInterface
    {
        return $this->naissance;
    }

    public function setNaissance(?DateTimeInterface $naissance): self
    {
        $this->naissance = $naissance;

        return $this;
    }

    public function getFkPays(): ?TPays
    {
        return $this->fk_pays;
    }

    public function setFkPays(?TPays $fk_pays): self
    {
        $this->fk_pays = $fk_pays;

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
            $tArticle->setFkUser($this);
        }

        return $this;
    }

    public function removeTArticle(TArticle $tArticle): self
    {
        if ($this->tArticles->removeElement($tArticle)) {
            // set the owning side to null (unless already changed)
            if ($tArticle->getFkUser() === $this) {
                $tArticle->setFkUser(null);
            }
        }

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
            $tComment->setFkUser($this);
        }

        return $this;
    }

    public function removeTComment(TComment $tComment): self
    {
        if ($this->tComments->removeElement($tComment)) {
            // set the owning side to null (unless already changed)
            if ($tComment->getFkUser() === $this) {
                $tComment->setFkUser(null);
            }
        }

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function serialize()
    {
        // TODO: Implement serialize() method.
    }

    public function unserialize($data)
    {
        // TODO: Implement unserialize() method.
    }

    public function getSalt()
    {
        // TODO: Implement getSalt() method.
        return null;
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
        return $this;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }
}
