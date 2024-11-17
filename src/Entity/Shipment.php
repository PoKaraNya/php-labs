<?php

namespace App\Entity;

use App\Repository\ShipmentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\ArrayShape;
use JsonSerializable;

/**
 *
 */
#[ORM\Entity(repositoryClass: ShipmentRepository::class)]
class Shipment implements JsonSerializable
{
    /**
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var Order|null
     */
    #[ORM\ManyToOne(inversedBy: 'shipments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Order $orderId = null;

    /**
     * @var \DateTimeInterface|null
     */
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $shipmentDate = null;

    /**
     * @var \DateTimeInterface|null
     */
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $deliveryDate = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255)]
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
     * @return \DateTimeInterface|null
     */
    public function getShipmentDate(): ?\DateTimeInterface
    {
        return $this->shipmentDate;
    }

    /**
     * @param \DateTimeInterface $shipmentDate
     * @return $this
     */
    public function setShipmentDate(\DateTimeInterface $shipmentDate): static
    {
        $this->shipmentDate = $shipmentDate;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getDeliveryDate(): ?\DateTimeInterface
    {
        return $this->deliveryDate;
    }

    /**
     * @param \DateTimeInterface $deliveryDate
     * @return $this
     */
    public function setDeliveryDate(\DateTimeInterface $deliveryDate): static
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
     * @return mixed
     */
    #[ArrayShape(['id' => "int|null", 'orderId' => "\App\Entity\Order|null", 'shipmentDate' => "null|string", 'deliveryDate' => "null|string", 'status' => "null|string"])] public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->getId(),
            'orderId' => $this->getOrderId(),
            'shipmentDate' => $this->getShipmentDate()?->format('Y-m-d H:i:s'),
            'deliveryDate' => $this->getDeliveryDate()?->format('Y-m-d H:i:s'),
            'status' => $this->getStatus(),
        ];
    }

}
