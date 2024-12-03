<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\OrderRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\ArrayShape;
use JsonSerializable;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 *
 */
#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
#[ApiResource(
    operations: [
        new Get(
            normalizationContext: ['groups' => 'get:item:`order`'],
        ),
        new GetCollection(
            normalizationContext: ['groups' => 'get:collection:`order`']
        ),
        new Post(
            normalizationContext: ['groups' => 'get:item:`order`'],
            denormalizationContext: ['groups' => 'post:collection:`order`']
        ),
        new Patch(
            normalizationContext: ['groups' => 'get:item:`order`'],
            denormalizationContext: ['groups' => 'patch:item:`order`']
        ),
        new Delete(),
    ],
)]
class Order implements JsonSerializable
{
    /**
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['get:item:`order`', 'get:collection:`order`'])]
    private ?int $id = null;

    /**
     * @var DateTimeInterface|null
     */
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotNull(message: 'Order date cannot be null.')]
    #[Assert\Type(
        type: DateTimeInterface::class,
        message: 'Order date must be a valid date and time.'
    )]
    #[Groups([
        'get:item:`order`',
        'get:collection:`order`',
        'post:collection:`order`',
        'patch:item:`order`'
    ])]
    private ?DateTimeInterface $orderDate = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    #[Assert\NotNull(message: 'Status cannot be null.')]
    #[Assert\Choice(
        choices: ['Pending', 'Processing', 'Shipped', 'Completed', 'Cancelled'],
        message: 'Status must be one of "Pending", "Processing", "Shipped", "Completed", or "Cancelled".'
    )]
    #[Groups([
        'get:item:`order`',
        'get:collection:`order`',
        'post:collection:`order`',
        'patch:item:`order`'
    ])]
    private ?string $status = null;

    /**
     * @var Customer|null
     */
    #[ORM\ManyToOne(inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: 'Customer must be associated with the order.')]
    #[Groups([
        'get:item:`order`',
        'get:collection:`order`',
        'post:collection:`order`',
        'patch:item:`order`'
    ])]
    private ?Customer $customer = null;

    /**
     * @var Collection<int, OrderItem>
     */
    #[ORM\OneToMany(targetEntity: OrderItem::class, mappedBy: 'orderId', cascade: ['persist', 'remove'])]
    #[Assert\Valid]
    #[Groups(['get:item:`order`'])]
    private Collection $orderItems;

    /**
     * @var Collection<int, Shipment>
     */
    #[ORM\OneToMany(targetEntity: Shipment::class, mappedBy: 'orderId', cascade: ['persist', 'remove'])]
    #[Assert\Valid]
    #[Groups(['get:item:`order`'])]
    private Collection $shipments;

    /**
     *
     */
    public function __construct()
    {
        $this->orderItems = new ArrayCollection();
        $this->shipments = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getOrderDate(): ?DateTimeInterface
    {
        return $this->orderDate;
    }

    /**
     * @param DateTimeInterface $orderDate
     * @return $this
     */
    public function setOrderDate(DateTimeInterface $orderDate): static
    {
        $this->orderDate = $orderDate;

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
     * @return Customer|null
     */
    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    /**
     * @param Customer|null $customer
     * @return $this
     */
    public function setCustomer(?Customer $customer): static
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * @return Collection<int, OrderItem>
     */
    public function getOrderItems(): Collection
    {
        return $this->orderItems;
    }

    /**
     * @param OrderItem $orderItem
     * @return $this
     */
    public function addOrderItem(OrderItem $orderItem): static
    {
        if (!$this->orderItems->contains($orderItem)) {
            $this->orderItems->add($orderItem);
            $orderItem->setOrder($this);
        }

        return $this;
    }

    /**
     * @param OrderItem $orderItem
     * @return $this
     */
    public function removeOrderItem(OrderItem $orderItem): static
    {
        if ($this->orderItems->removeElement($orderItem)) {
            // set the owning side to null (unless already changed)
            if ($orderItem->getOrder() === $this) {
                $orderItem->setOrder(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Shipment>
     */
    public function getShipments(): Collection
    {
        return $this->shipments;
    }

    /**
     * @param Shipment $shipment
     * @return $this
     */
    public function addShipment(Shipment $shipment): static
    {
        if (!$this->shipments->contains($shipment)) {
            $this->shipments->add($shipment);
            $shipment->setOrder($this);
        }

        return $this;
    }

    /**
     * @param Shipment $shipment
     * @return $this
     */
    public function removeShipment(Shipment $shipment): static
    {
        if ($this->shipments->removeElement($shipment)) {
            // set the owning side to null (unless already changed)
            if ($shipment->getOrder() === $this) {
                $shipment->setOrder(null);
            }
        }

        return $this;
    }

    /**
     * @return array
     */
   #[ArrayShape(['id' => "int|null", 'customerId' => "\App\Entity\Customer|null", 'orderDate' => "string", 'status' => "null|string"])] public function jsonSerialize(): array
   {
        return [
            'id' => $this->getId(),
            'customerId' => $this->getCustomer(),
            'orderDate' => $this->getOrderDate()->format('Y-m-d'),
            'status' => $this->getStatus(),
        ];
    }


}
