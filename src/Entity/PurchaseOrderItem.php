<?php

namespace App\Entity;

use App\Repository\PurchaseOrderItemRepository;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\ArrayShape;
use JsonSerializable;

/**
 *
 */
#[ORM\Entity(repositoryClass: PurchaseOrderItemRepository::class)]
class PurchaseOrderItem implements JsonSerializable
{
    /**
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var PurchaseOrder|null
     */
    #[ORM\ManyToOne(inversedBy: 'purchaseOrderItem')]
    #[ORM\JoinColumn(nullable: false)]
    private ?PurchaseOrder $purchaseOrder = null;

    /**
     * @var Product|null
     */
    #[ORM\ManyToOne(inversedBy: 'purchaseOrderItem')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Product $product = null;

    /**
     * @var int|null
     */
    #[ORM\Column]
    private ?int $quantity = null;

    /**
     * @var int|null
     */
    #[ORM\Column]
    private ?int $pricePerUnit = null;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return PurchaseOrder|null
     */
    public function getPurchaseOrder(): ?PurchaseOrder
    {
        return $this->purchaseOrder;
    }

    /**
     * @param PurchaseOrder|null $purchaseOrder
     * @return $this
     */
    public function setPurchaseOrder(?PurchaseOrder $purchaseOrder): static
    {
        $this->purchaseOrder = $purchaseOrder;

        return $this;
    }

    /**
     * @return Product|null
     */
    public function getProduct(): ?Product
    {
        return $this->product;
    }

    /**
     * @param Product|null $product
     * @return $this
     */
    public function setProduct(?Product $product): static
    {
        $this->product = $product;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     * @return $this
     */
    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getPricePerUnit(): ?int
    {
        return $this->pricePerUnit;
    }

    /**
     * @param int $pricePerUnit
     * @return $this
     */
    public function setPricePerUnit(int $pricePerUnit): static
    {
        $this->pricePerUnit = $pricePerUnit;

        return $this;
    }


    /**
     * @return mixed
     */
    #[ArrayShape(['id' => "int|null", 'purchaseOrderId' => "\App\Entity\PurchaseOrder|null", 'productId' => "\App\Entity\Product|null", 'quantity' => "int|null", 'pricePerUnit' => "int|null"])] public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->getId(),
            'purchaseOrderId' => $this->getPurchaseOrder(),
            'productId' => $this->getProduct(),
            'quantity' => $this->getQuantity(),
            'pricePerUnit' => $this->getPricePerUnit(),
        ];
    }

}
