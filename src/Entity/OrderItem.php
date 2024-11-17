<?php

namespace App\Entity;

use App\Repository\OrderItemRepository;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\ArrayShape;
use JsonSerializable;


#[ORM\Entity(repositoryClass: OrderItemRepository::class)]
class OrderItem implements JsonSerializable
{
    /**
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

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
     * @var Order|null
     */
    #[ORM\ManyToOne(inversedBy: 'orderItems')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Order $orderId = null;

    /**
     * @var Product|null
     */
    #[ORM\ManyToOne(inversedBy: 'orderItems')]
    #[ORM\JoinColumn(nullable: false)]
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
    public function getOrderId(): ?Order
    {
        return $this->orderId;
    }

    /**
     * @param Order|null $orderId
     * @return $this
     */
    public function setOrderId(?Order $orderId): static
    {
        $this->orderId = $orderId;

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
     * @return mixed
     */
    #[ArrayShape(['id' => "int|null", 'orderId' => "\App\Entity\Order|null", 'quantity' => "int|null", 'pricePerUnit' => "int|null", 'product' => "int|null"])] public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->getId(),
            'orderId' => $this->getOrderId(),
            'quantity' => $this->getQuantity(),
            'pricePerUnit' => $this->getPricePerUnit(),
            'product' => $this->getProduct()->getId(),
        ];
    }

}
