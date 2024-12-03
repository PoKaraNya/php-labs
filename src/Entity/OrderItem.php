<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\OrderItemRepository;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\ArrayShape;
use JsonSerializable;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: OrderItemRepository::class)]
#[ApiResource(
    operations: [
        new Get(
            normalizationContext: ['groups' => 'get:item:order-item']
        ),
        new GetCollection(
            normalizationContext: ['groups' => 'get:collection:order-item']
        ),
        new Post(
            normalizationContext: ['groups' => 'get:item:order-item'],
            denormalizationContext: ['groups' => 'post:collection:order-item']
        ),
        new Patch(
            normalizationContext: ['groups' => 'get:item:order-item'],
            denormalizationContext: ['groups' => 'patch:item:order-item']
        ),
        new Delete(),
    ],
)]
class OrderItem implements JsonSerializable
{
    /**
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['get:item:order-item', 'get:collection:order-item'])]
    private ?int $id = null;

    /**
     * @var int|null
     */
    #[ORM\Column]
    #[Assert\NotNull(message: 'Quantity cannot be null.')]
    #[Assert\GreaterThanOrEqual(value: 1, message: 'Quantity must be at least 1.')]
    #[Groups([
        'get:item:order-item',
        'get:collection:order-item',
        'post:collection:order-item',
        'patch:item:order-item'
    ])]
    private ?int $quantity = null;

    /**
     * @var int|null
     */
    #[ORM\Column]
    #[Assert\NotNull(message: 'Price per unit cannot be null.')]
    #[Assert\GreaterThanOrEqual(value: 0, message: 'Price per unit must be a positive value.')]
    #[Groups([
        'get:item:order-item',
        'get:collection:order-item',
        'post:collection:order-item',
        'patch:item:order-item'
    ])]
    private ?int $pricePerUnit = null;

    /**
     * @var Order|null
     */
    #[ORM\ManyToOne(inversedBy: 'orderItems')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: 'Order cannot be null.')]
    #[Groups([
        'get:item:order-item',
        'get:collection:order-item',
        'post:collection:order-item',
        'patch:item:order-item'
    ])]
    private ?Order $order = null;

    /**
     * @var Product|null
     */
    #[ORM\ManyToOne(inversedBy: 'orderItems')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: 'Product cannot be null.')]
    #[Groups([
        'get:item:order-item',
        'get:collection:order-item',
        'post:collection:order-item',
        'patch:item:order-item'
    ])]
    private ?Product $product = null;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
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
     * @return Order|null
     */
    public function getOrder(): ?Order
    {
        return $this->order;
    }

    /**
     * @param Order|null $order
     * @return $this
     */
    public function setOrder(?Order $order): static
    {
        $this->order = $order;

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
     * @return array
     */
    #[ArrayShape(['id' => "int|null", 'order' => "\App\Entity\Order|null", 'product' => "\App\Entity\Product|null", 'quantity' => "int|null", 'pricePerUnit' => "int|null"])] public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'order' => $this->getOrder(),
            'product' => $this->getProduct(),
            'quantity' => $this->getQuantity(),
            'pricePerUnit' => $this->getPricePerUnit(),
        ];
    }

}
