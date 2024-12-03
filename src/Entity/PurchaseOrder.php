<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\PurchaseOrderRepository;
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
#[ORM\Entity(repositoryClass: PurchaseOrderRepository::class)]
#[ApiResource(
    operations: [
        new Get(
            normalizationContext: ['groups' => 'get:item:purchase-order'],
        ),
        new GetCollection(
            normalizationContext: ['groups' => 'get:collection:purchase-order']
        ),
        new Post(
            normalizationContext: ['groups' => 'get:item:purchase-order'],
            denormalizationContext: ['groups' => 'post:collection:purchase-order']
        ),
        new Patch(
            normalizationContext: ['groups' => 'get:item:purchase-order'],
            denormalizationContext: ['groups' => 'patch:item:purchase-order']
        ),
        new Delete(),
    ],
)]
class PurchaseOrder implements JsonSerializable
{
    /**
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['get:item:purchase-order', 'get:collection:purchase-order'])]
    private ?int $id = null;

    /**
     * @var Supplier|null
     */
    #[ORM\ManyToOne(inversedBy: 'purchaseOrders')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: 'Supplier cannot be null.')]
    #[Groups([
        'get:item:purchase-order',
        'get:collection:purchase-order',
        'post:collection:purchase-order',
        'patch:item:purchase-order'
    ])]
    private ?Supplier $supplier = null;

    /**
     * @var DateTimeInterface|null
     */
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotNull(message: 'Order date cannot be null.')]
    #[Assert\Type("\DateTimeInterface", message: 'The order date must be a valid date.')]
    #[Groups([
        'get:item:purchase-order',
        'get:collection:purchase-order',
        'post:collection:purchase-order',
        'patch:item:purchase-order'
    ])]
    private ?DateTimeInterface $orderDate = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    #[Assert\NotNull(message: 'Status cannot be null.')]
    #[Assert\Length(min: 3, max: 255, minMessage: 'Status must be at least {{ limit }} characters long.', maxMessage: 'Status cannot be longer than {{ limit }} characters.')]
    #[Groups([
        'get:item:purchase-order',
        'get:collection:purchase-order',
        'post:collection:purchase-order',
        'patch:item:purchase-order'
    ])]
    private ?string $status = null;

    /**
     * @var Collection<int, PurchaseOrderItem>
     */
    #[ORM\OneToMany(targetEntity: PurchaseOrderItem::class, mappedBy: 'purchaseOrder')]
    #[Groups(['get:item:purchase-order'])]
    private Collection $purchaseOrderItems;
    /**
     *
     */
    public function __construct()
    {
        $this->purchaseOrderItems = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Supplier|null
     */
    public function getSupplier(): ?Supplier
    {
        return $this->supplier;
    }

    /**
     * @param Supplier|null $supplier
     * @return $this
     */
    public function setSupplier(?Supplier $supplier): static
    {
        $this->supplier = $supplier;

        return $this;
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
     * @return Collection<int, PurchaseOrderItem>
     */
    public function getPurchaseOrderItems(): Collection
    {
        return $this->purchaseOrderItems;
    }

    /**
     * @param PurchaseOrderItem $purchaseOrderItem
     * @return $this
     */
    public function addPurchaseOrderItem(PurchaseOrderItem $purchaseOrderItem): static
    {
        if (!$this->purchaseOrderItems->contains($purchaseOrderItem)) {
            $this->purchaseOrderItems->add($purchaseOrderItem);
            $purchaseOrderItem->setPurchaseOrder($this);
        }

        return $this;
    }

    /**
     * @param PurchaseOrderItem $purchaseOrderItem
     * @return $this
     */
    public function removePurchaseOrderItem(PurchaseOrderItem $purchaseOrderItem): static
    {
        if ($this->purchaseOrderItems->removeElement($purchaseOrderItem)) {
            // set the owning side to null (unless already changed)
            if ($purchaseOrderItem->getPurchaseOrder() === $this) {
                $purchaseOrderItem->setPurchaseOrder(null);
            }
        }

        return $this;
    }

    /**
     * @return array
     */
    #[ArrayShape(['id' => "int|null", 'supplierId' => "\App\Entity\Supplier|null", 'orderDate' => "string", 'status' => "null|string"])] public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'supplierId' => $this->getSupplier(),
            'orderDate' => $this->getOrderDate()->format('Y-m-d'),
            'status' => $this->getStatus(),
        ];
    }

}
