<?php

namespace App\Entity;

use App\Repository\InventoryRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\ArrayShape;
use JsonSerializable;

#[ORM\Entity(repositoryClass: InventoryRepository::class)]
class Inventory implements JsonSerializable
{
    /**
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var Product|null
     */
    #[ORM\ManyToOne(inversedBy: 'inventories')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Product $product = null;

    /**
     * @var int|null
     */
    #[ORM\Column]
    private ?int $quantity = null;

    /**
     * @var \DateTimeInterface|null
     */
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $lastUpdated = null;

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
     * @return \DateTimeInterface|null
     */
    public function getLastUpdated(): ?\DateTimeInterface
    {
        return $this->lastUpdated;
    }

    /**
     * @param \DateTimeInterface $lastUpdated
     * @return $this
     */
    public function setLastUpdated(\DateTimeInterface $lastUpdated): static
    {
        $this->lastUpdated = $lastUpdated;

        return $this;
    }

    /**
     * @return mixed
     */
    #[ArrayShape(['id' => "int|null", 'productId' => "\App\Entity\Product|null", 'quantity' => "int|null", 'lastUpdated' => "string"])] public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->getId(),
            'productId' => $this->getProduct(),
            'quantity' => $this->getQuantity(),
            'lastUpdated' => $this->getLastUpdated()->format('Y-m-d H:i:s')
        ];
    }

}
