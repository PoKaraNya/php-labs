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

/**
 *
 */
#[ORM\Entity()]
#[ApiResource(
    operations: [
        new Get(
            normalizationContext: ['groups' => 'get:item:shipment'],
        ),
        new GetCollection(
            normalizationContext: ['groups' => 'get:collection:shipment']
        ),
        new Post(
            normalizationContext: ['groups' => 'get:item:shipment'],
            denormalizationContext: ['groups' => 'post:collection:shipment']
        ),
        new Patch(
            normalizationContext: ['groups' => 'get:item:shipment'],
            denormalizationContext: ['groups' => 'patch:item:shipment']
        ),
        new Delete(),
    ],
)]
class Shipment implements JsonSerializable
{
    /**
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['get:item:shipment', 'get:collection:shipment'])]
    private ?int $id = null;

    /**
     * @var Order|null
     */
    #[ORM\ManyToOne(inversedBy: 'shipments')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: 'Order cannot be null.')]
    #[Groups([
        'get:item:shipment',
        'get:collection:shipment',
        'post:collection:shipment',
        'patch:item:shipment'
    ])]
    private ?Order $order = null;

    /**
     * @var DateTimeInterface|null
     */
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotNull(message: 'Shipment date cannot be null.')]
    #[Assert\Type("\DateTimeInterface", message: 'Shipment date must be a valid date time.')]
    #[Groups([
        'get:item:shipment',
        'get:collection:shipment',
        'post:collection:shipment',
        'patch:item:shipment'
    ])]
    private ?DateTimeInterface $shipmentDate = null;

    /**
     * @var DateTimeInterface|null
     */
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotNull(message: 'Delivery date cannot be null.')]
    #[Assert\Type("\DateTimeInterface", message: 'Delivery date must be a valid date time.')]
    #[Groups([
        'get:item:shipment',
        'get:collection:shipment',
        'post:collection:shipment',
        'patch:item:shipment'
    ])]
    private ?DateTimeInterface $deliveryDate = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Status cannot be blank.')]
    #[Assert\Choice(choices: ['pending', 'shipped', 'delivered'], message: 'Invalid status value.')]
    #[Groups([
        'get:item:shipment',
        'get:collection:shipment',
        'post:collection:shipment',
        'patch:item:shipment'
    ])]
    private ?string $status = null;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
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
     * @return DateTimeInterface|null
     */
    public function getShipmentDate(): ?DateTimeInterface
    {
        return $this->shipmentDate;
    }

    /**
     * @param DateTimeInterface $shipmentDate
     * @return $this
     */
    public function setShipmentDate(DateTimeInterface $shipmentDate): static
    {
        $this->shipmentDate = $shipmentDate;

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getDeliveryDate(): ?DateTimeInterface
    {
        return $this->deliveryDate;
    }

    /**
     * @param DateTimeInterface $deliveryDate
     * @return $this
     */
    public function setDeliveryDate(DateTimeInterface $deliveryDate): static
    {
        $this->deliveryDate = $deliveryDate;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @param string $status
     * @return $this
     */
    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return array
     */
    #[ArrayShape(['id' => "int|null", 'order' => "\App\Entity\Order|null", 'shipmentDate' => "null|string", 'deliveryDate' => "null|string", 'status' => "null|string"])] public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'order' => $this->getOrder(),
            'shipmentDate' => $this->getShipmentDate()?->format('Y-m-d H:i:s'),
            'deliveryDate' => $this->getDeliveryDate()?->format('Y-m-d H:i:s'),
            'status' => $this->getStatus(),
        ];
    }

}
