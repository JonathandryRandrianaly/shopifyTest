<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Supplier::class)
     */
    private $supplier;

    /**
     * @ORM\Column(type="string", length=180)
     */
    private $reference;

    /**
     * @ORM\Column(type="string", length=180, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="decimal", precision=21, scale=2, nullable=true)
     */
    private $shopifyPrice;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSupplier(): ?Supplier
    {
        return $this->supplier;
    }

    public function setSupplier(?Supplier $supplier): self
    {
        $this->supplier = $supplier;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getShopifyPrice(): ?string
    {
        return $this->shopifyPrice;
    }

    public function setShopifyPrice(?string $shopifyPrice): self
    {
        $this->shopifyPrice = $shopifyPrice;

        return $this;
    }
}
