<?php

namespace App\Entity;

use App\Repository\StockRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StockRepository::class)
 */
class Stock
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class)
     */
    private $product;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $date;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $quantityIn;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $quantityOut;

    /**
     * @ORM\Column(type="decimal", precision=21, scale=2, nullable=true)
     */
    private $purchasePrice;

    /**
     * @ORM\Column(type="decimal", precision=21, scale=2, nullable=true)
     */
    private $shopifyPrice;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getQuantityIn(): ?int
    {
        return $this->quantityIn;
    }

    public function setQuantityIn(?int $quantityIn): self
    {
        $this->quantityIn = $quantityIn;

        return $this;
    }

    public function getQuantityOut(): ?int
    {
        return $this->quantityOut;
    }

    public function setQuantityOut(?int $quantityOut): self
    {
        $this->quantityOut = $quantityOut;

        return $this;
    }

    public function getPurchasePrice(): ?string
    {
        return $this->purchasePrice;
    }

    public function setPurchasePrice(?string $purchasePrice): self
    {
        $this->purchasePrice = $purchasePrice;

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
