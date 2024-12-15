<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\ArrayShape;
use JsonSerializable;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity()]
#[ApiResource(
    operations: [
        new Get(
            normalizationContext: ['groups' => 'get:item:inventory'],
        ),
        new GetCollection(
            normalizationContext: ['groups' => 'get:collection:inventory']
        ),
        new Post(
            normalizationContext: ['groups' => 'get:item:inventory'],
            denormalizationContext: ['groups' => 'post:collection:inventory']
        ),
        new Patch(
            normalizationContext: ['groups' => 'get:item:inventory'],
            denormalizationContext: ['groups' => 'patch:item:inventory']
        ),
        new Delete(),
    ],
)]
class Inventory implements JsonSerializable
{
    /**
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['get:item:inventory', 'get:collection:inventory'])]
    private ?int $id = null;

    /**
     * @var Product|null
     */
    #[ORM\ManyToOne(inversedBy: 'inventories')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: 'Product must be associated with an inventory record.')]
    #[Groups([
        'get:item:inventory',
        'get:collection:inventory',
        'post:collection:inventory',
        'patch:item:inventory'
    ])]
    private ?Product $product = null;

    /**
     * @var int|null
     */
    #[ORM\Column]
    #[Assert\NotNull(message: 'Quantity cannot be null.')]
    #[Assert\PositiveOrZero(message: 'Quantity must be zero or a positive value.')]
    #[Groups([
        'get:item:inventory',
        'get:collection:inventory',
        'post:collection:inventory',
        'patch:item:inventory'
    ])]
    private ?int $quantity = null;

    /**
     * @var DateTimeInterface|null
     */
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotNull(message: 'Last updated date cannot be null.')]
    #[Assert\Type(
        type: DateTimeInterface::class,
        message: 'Last updated must be a valid date and time.'
    )]
    #[Groups([
        'get:item:inventory',
        'get:collection:inventory',
        'post:collection:inventory',
        'patch:item:inventory'
    ])]
    private ?DateTimeInterface $lastUpdated = null;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
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
     * @return DateTimeInterface|null
     */
    public function getLastUpdated(): ?DateTimeInterface
    {
        return $this->lastUpdated;
    }

    /**
     * @param DateTimeInterface $lastUpdated
     * @return $this
     */
    public function setLastUpdated(DateTimeInterface $lastUpdated): static
    {
        $this->lastUpdated = $lastUpdated;

        return $this;
    }

    /**
     * @return array
     */
    #[ArrayShape(['id' => "int|null", 'productId' => "\App\Entity\Product|null", 'quantity' => "int|null", 'lastUpdated' => "string"])] public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'productId' => $this->getProduct(),
            'quantity' => $this->getQuantity(),
            'lastUpdated' => $this->getLastUpdated()->format('Y-m-d H:i:s')
        ];
    }

}
