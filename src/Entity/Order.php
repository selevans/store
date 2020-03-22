<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OrderRepository")
 */
class Order
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
    private $orderAt;

    /**
     *
     * @ORM\OneToMany(targetEntity="App\Entity\OrderDetail", mappedBy="order")
     */
    private $orderdetail;

    public function __construct()
    {
        $this->orderdetail = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrderAt(): ?\DateTimeInterface
    {
        return $this->orderAt;
    }

    public function setOrderAt(\DateTimeInterface $orderAt): self
    {
        $this->orderAt = $orderAt;

        return $this;
    }

    /**
     * @return Collection|OrderDetail[]
     */
    public function getOrderdetail(): Collection
    {
        return $this->orderdetail;
    }

    public function addOrderdetail(OrderDetail $orderdetail): self
    {
        if (!$this->orderdetail->contains($orderdetail)) {
            $this->orderdetail[] = $orderdetail;
            $orderdetail->setOrder($this);
        }

        return $this;
    }

    public function removeOrderdetail(OrderDetail $orderdetail): self
    {
        if ($this->orderdetail->contains($orderdetail)) {
            $this->orderdetail->removeElement($orderdetail);
            // set the owning side to null (unless already changed)
            if ($orderdetail->getOrder() === $this) {
                $orderdetail->setOrder(null);
            }
        }

        return $this;
    }
}
