<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\ArrayShape;
use JsonSerializable;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 *
 */
#[ORM\Entity()]
#[ApiResource(
    operations: [
        new Get(
            normalizationContext: ['groups' => 'get:item:purchase-order-item'],
        ),
        new GetCollection(
            normalizationContext: ['groups' => 'get:collection:purchase-order-item']
        ),
        new Post(
            normalizationContext: ['groups' => 'get:item:purchase-order-item'],
            denormalizationContext: ['groups' => 'post:collection:purchase-order-item']
        ),
        new Patch(
            normalizationContext: ['groups' => 'get:item:purchase-order-item'],
            denormalizationContext: ['groups' => 'patch:item:purchase-order-item']
        ),
        new Delete(),
    ],
)]
class PurchaseOrderItem implements JsonSerializable
{
    /**
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['get:item:purchase-order-item', 'get:collection:purchase-order-item'])]
    private ?int $id = null;

    /**
     * @var PurchaseOrder|null
     */
    #[ORM\ManyToOne(inversedBy: 'purchaseOrderItems')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: 'Purchase order cannot be null.')]
    #[Groups([
        'get:item:purchase-order-item',
        'get:collection:purchase-order-item',
        'post:collection:purchase-order-item',
        'patch:item:purchase-order-item'
    ])]
    private ?PurchaseOrder $purchaseOrder = null;

    /**
     * @var Product|null
     */
    #[ORM\ManyToOne(inversedBy: 'purchaseOrderItems')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: 'Product cannot be null.')]
    #[Groups([
        'get:item:purchase-order-item',
        'get:collection:purchase-order-item',
        'post:collection:purchase-order-item',
        'patch:item:purchase-order-item'
    ])]
    private ?Product $product = null;

    /**
     * @var int|null
     */
    #[ORM\Column]
    #[Assert\Positive(message: 'Quantity must be a positive number.')]
    #[Assert\NotNull(message: 'Quantity cannot be null.')]
    #[Groups([
        'get:item:purchase-order-item',
        'get:collection:purchase-order-item',
        'post:collection:purchase-order-item',
        'patch:item:purchase-order-item'
    ])]
    private ?int $quantity = null;

    /**
     * @var int|null
     */
    #[ORM\Column]
    #[Assert\Positive(message: 'Price per unit must be a positive number.')]
    #[Assert\NotNull(message: 'Price per unit cannot be null.')]
    #[Groups([
        'get:item:purchase-order-item',
        'get:collection:purchase-order-item',
        'post:collection:purchase-order-item',
        'patch:item:purchase-order-item'
    ])]
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
     * @return array
     */
    #[ArrayShape(['id' => "int|null", 'purchaseOrderId' => "\App\Entity\PurchaseOrder|null", 'productId' => "\App\Entity\Product|null", 'quantity' => "int|null", 'pricePerUnit' => "int|null"])] public function jsonSerialize(): array
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
