<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CommandRepository")
 */
class Command
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createAt;

    /**
     *
     * @ORM\OneToMany(targetEntity="App\Entity\CommandDetail", mappedBy="command")
     */
    private $commanddetail;

    public function __construct()
    {
        $this->commanddetail = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreateAt(): ?\DateTimeInterface
    {
        return $this->createAt;
    }

    public function setCreateAt(\DateTimeInterface $createAt): self
    {
        $this->createAt = $createAt;

        return $this;
    }

    /**
     * @return Collection|CommandDetail[]
     */
    public function getCommanddetail(): Collection
    {
        return $this->commanddetail;
    }

    public function addCommanddetail(CommandDetail $commanddetail): self
    {
        if (!$this->commanddetail->contains($commanddetail)) {
            $this->commanddetail[] = $commanddetail;
            $commanddetail->setCommand($this);
        }

        return $this;
    }

    public function removeCommanddetail(CommandDetail $commanddetail): self
    {
        if ($this->commanddetail->contains($commanddetail)) {
            $this->commanddetail->removeElement($commanddetail);
            // set the owning side to null (unless already changed)
            if ($commanddetail->getCommand() === $this) {
                $commanddetail->setCommand(null);
            }
        }

        return $this;
    }

    public function __toString() {
        return $this->createAt;
    }
}
