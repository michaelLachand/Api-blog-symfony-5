<?php


namespace App\Entity\base;


use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

trait TraitEntity
{
    /**
     * @ORM\Column(type="datetime")
     */
    private ?DateTimeInterface $date_save = null;

    /**
     * @ORM\Column(type="boolean")
     */
    private ?bool $active = true;

    public function getDateSave(): ?DateTimeInterface
    {
        return $this->date_save;
    }

    public function setDateSave(DateTimeInterface $date_save): self
    {
        $this->date_save = $date_save;

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }
}