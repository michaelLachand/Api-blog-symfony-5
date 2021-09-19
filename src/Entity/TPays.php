<?php

namespace App\Entity;

use App\Entity\base\TraitEntity;
use App\Repository\TPaysRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TPaysRepository::class)
 * @ORM\Table(name="t_pays")
 */
class TPays
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
    private ?string $nom = null;

    /**
     * @ORM\Column(type="string", length=1000, nullable=true)
     */
    private ?string $description = null;

    /**
     * @ORM\OneToMany(targetEntity=TUser::class, mappedBy="fk_pays")
     */
    private Collection $tUsers;

    public function __construct()
    {
        $this->tUsers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

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
     * @return Collection|TUser[]
     */
    public function getTUsers(): Collection
    {
        return $this->tUsers;
    }

    public function addTUser(TUser $tUser): self
    {
        if (!$this->tUsers->contains($tUser)) {
            $this->tUsers[] = $tUser;
            $tUser->setFkPays($this);
        }

        return $this;
    }

    public function removeTUser(TUser $tUser): self
    {
        if ($this->tUsers->removeElement($tUser)) {
            // set the owning side to null (unless already changed)
            if ($tUser->getFkPays() === $this) {
                $tUser->setFkPays(null);
            }
        }

        return $this;
    }
}
