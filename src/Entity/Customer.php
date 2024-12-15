<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
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
            normalizationContext: ['groups' => 'get:item:customer'],
        ),
        new GetCollection(
            normalizationContext: ['groups' => 'get:collection:customer']
        ),
        new Post(
            normalizationContext: ['groups' => 'get:item:customer'],
            denormalizationContext: ['groups' => 'post:collection:customer']
        ),
        new Patch(
            normalizationContext: ['groups' => 'get:item:customer'],
            denormalizationContext: ['groups' => 'patch:item:customer']
        ),
        new Delete(),
    ],
)]
class Customer implements JsonSerializable
{
    /**
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['get:item:customer', 'get:collection:customer'])]
    private ?int $id = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Name cannot be blank.')]
    #[Assert\Length(
        max: 255,
        maxMessage: 'Name cannot be longer than {{ limit }} characters.'
    )]
    #[Groups([
        'get:item:customer',
        'get:collection:customer',
        'post:collection:customer',
        'patch:item:customer'
    ])]
    private ?string $name = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Email cannot be blank.')]
    #[Assert\Email(message: 'The email {{ value }} is not a valid email.')]
    #[Assert\Length(
        max: 255,
        maxMessage: 'Email cannot be longer than {{ limit }} characters.'
    )]
    #[Groups([
        'get:item:customer',
        'get:collection:customer',
        'post:collection:customer',
        'patch:item:customer'
    ])]
    private ?string $email = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Phone number cannot be blank.')]
    #[Assert\Regex(
        pattern: '/^\+?[0-9]{10,15}$/',
        message: 'Phone number must be valid and contain 10 to 15 digits.'
    )]
    #[Groups([
        'get:item:customer',
        'get:collection:customer',
        'post:collection:customer',
        'patch:item:customer'
    ])]
    private ?string $phone = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(
        max: 255,
        maxMessage: 'Address cannot be longer than {{ limit }} characters.'
    )]
    #[Groups([
        'get:item:customer',
        'get:collection:customer',
        'post:collection:customer',
        'patch:item:customer'
    ])]
    private ?string $address = null;

    /**
     * @var Collection
     */
    #[ORM\OneToMany(targetEntity: Order::class, mappedBy: 'customer', orphanRemoval: true)]
    #[Assert\All([
        new Assert\Type(type: Order::class, message: 'Each item must be a valid Order object.')
    ])]
    #[Groups(['get:item:customer'])]
    private Collection $orders;

    #[ORM\OneToOne(targetEntity: User::class, inversedBy: 'customer', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['get:item:customer', 'get:collection:customer', 'post:collection:customer', 'patch:item:customer'])]
    private ?User $user = null;

    /**
     *
     */
    public function __construct()
    {
        $this->orders = new ArrayCollection();
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
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return $this
     */
    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     * @return $this
     */
    public function setPhone(string $phone): static
    {
        $this->phone = $phone;

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
     * @return Collection<int, Order>
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    /**
     * @param Order $order
     * @return $this
     */
    public function addOrder(Order $order): static
    {
        if (!$this->orders->contains($order)) {
            $this->orders->add($order);
            $order->setCustomer($this);
        }

        return $this;
    }

    /**
     * @param Order $order
     * @return $this
     */
    public function removeOrder(Order $order): static
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getCustomer() === $this) {
                $order->setCustomer(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return array
     */
    #[ArrayShape(['id' => "int|null", 'name' => "null|string", 'email' => "null|string", 'phone' => "null|string", 'address' => "null|string"])] public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'email' => $this->getEmail(),
            'phone' => $this->getPhone(),
            'address' => $this->getAddress()
        ];
    }

}
