<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Action\Supplier\SupplierAction;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
            normalizationContext: ['groups' => 'get:item:supplier'],
        ),
        new GetCollection(
            normalizationContext: ['groups' => 'get:collection:supplier']
        ),
        new Post(
            normalizationContext: ['groups' => 'get:item:supplier'],
            denormalizationContext: ['groups' => 'post:collection:supplier']
        ),
        new Patch(
            normalizationContext: ['groups' => 'get:item:supplier'],
            denormalizationContext: ['groups' => 'patch:item:supplier']
        ),
        new Delete(),
        new Get(
            uriTemplate: '/suppliers/{id}/products',
            controller: SupplierAction::class,
            name: 'supplier_get_products'
        ),
    ],
)]
class Supplier implements JsonSerializable
{
    /**
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['get:item:supplier', 'get:collection:supplier'])]
    private ?int $id = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Name cannot be blank.')]
    #[Groups([
        'get:item:supplier',
        'get:collection:supplier',
        'post:collection:supplier',
        'patch:item:supplier'
    ])]
    private ?string $name = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Contact name cannot be blank.')]
    #[Groups([
        'get:item:supplier',
        'get:collection:supplier',
        'post:collection:supplier',
        'patch:item:supplier'
    ])]
    private ?string $contactName = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Contact phone cannot be blank.')]
    #[Assert\Regex(
        pattern: '/^\+?[0-9]*$/',
        message: 'Contact phone must contain only numbers and an optional "+" at the beginning.'
    )]
    #[Groups([
        'get:item:supplier',
        'get:collection:supplier',
        'post:collection:supplier',
        'patch:item:supplier'
    ])]
    private ?string $contactPhone = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Contact email cannot be blank.')]
    #[Assert\Email(message: 'Invalid email address.')]
    #[Groups([
        'get:item:supplier',
        'get:collection:supplier',
        'post:collection:supplier',
        'patch:item:supplier'
    ])]
    private ?string $contactEmail = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Address cannot be blank.')]
    #[Groups([
        'get:item:supplier',
        'get:collection:supplier',
        'post:collection:supplier',
        'patch:item:supplier'
    ])]
    private ?string $address = null;

    /**
     * @var Collection<int, Product>
     */
    #[ORM\OneToMany(targetEntity: Product::class, mappedBy: 'supplier')]
    #[Groups(['get:item:supplier'])]
    private Collection $products;

    /**
     * @var Collection<int, PurchaseOrder>
     */
    #[ORM\OneToMany(targetEntity: PurchaseOrder::class, mappedBy: 'supplier')]
    #[Groups(['get:item:supplier'])]
    private Collection $purchaseOrders;
    /**
     *
     */
    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->purchaseOrders = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getContactName(): ?string
    {
        return $this->contactName;
    }

    /**
     * @param string $contactName
     * @return $this
     */
    public function setContactName(string $contactName): static
    {
        $this->contactName = $contactName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getContactPhone(): ?string
    {
        return $this->contactPhone;
    }

    /**
     * @param string $contactPhone
     * @return $this
     */
    public function setContactPhone(string $contactPhone): static
    {
        $this->contactPhone = $contactPhone;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getContactEmail(): ?string
    {
        return $this->contactEmail;
    }

    /**
     * @param string $contactEmail
     * @return $this
     */
    public function setContactEmail(string $contactEmail): static
    {
        $this->contactEmail = $contactEmail;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * @param string $address
     * @return $this
     */
    public function setAddress(string $address): static
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return Collection<int, Product>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    /**
     * @param Product $product
     * @return $this
     */
    public function addProduct(Product $product): static
    {
        if (!$this->products->contains($product)) {
            $this->products->add($product);
            $product->setSupplier($this);
        }

        return $this;
    }

    /**
     * @param Product $product
     * @return $this
     */
    public function removeProduct(Product $product): static
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getSupplier() === $this) {
                $product->setSupplier(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, PurchaseOrder>
     */
    public function getPurchaseOrders(): Collection
    {
        return $this->purchaseOrders;
    }

    /**
     * @param PurchaseOrder $purchaseOrder
     * @return $this
     */
    public function addPurchaseOrder(PurchaseOrder $purchaseOrder): static
    {
        if (!$this->purchaseOrders->contains($purchaseOrder)) {
            $this->purchaseOrders->add($purchaseOrder);
            $purchaseOrder->setSupplier($this);
        }

        return $this;
    }

    /**
     * @param PurchaseOrder $purchaseOrder
     * @return $this
     */
    public function removePurchaseOrder(PurchaseOrder $purchaseOrder): static
    {
        if ($this->purchaseOrders->removeElement($purchaseOrder)) {
            // set the owning side to null (unless already changed)
            if ($purchaseOrder->getSupplier() === $this) {
                $purchaseOrder->setSupplier(null);
            }
        }

        return $this;
    }

    /**
     * @return array
     */
    #[ArrayShape(['id' => "int|null", 'name' => "null|string", 'contactName' => "null|string", 'contactPhone' => "null|string", 'contactEmail' => "null|string", 'address' => "null|string", "6" => "string"])] public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'contactName' => $this->getContactName(),
            'contactPhone' => $this->getContactPhone(),
            'contactEmail' => $this->getContactEmail(),
            'address' => $this->getAddress(),
        ];
    }

}
