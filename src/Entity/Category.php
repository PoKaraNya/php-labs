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
            normalizationContext: ['groups' => 'get:item:category']
        ),
        new GetCollection(
            normalizationContext: ['groups' => 'get:collection:category']
        ),
        new Post(
            normalizationContext: ['groups' => 'get:item:category'],
            denormalizationContext: ['groups' => 'post:collection:category']
        ),
        new Patch(
            normalizationContext: ['groups' => 'get:item:category'],
            denormalizationContext: ['groups' => 'patch:item:category']
        ),
        new Delete(),
    ],
)]
class Category implements JsonSerializable
{
    /**
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['get:item:category', 'get:collection:category'])]
    private ?int $id = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(
        max: 255,
        maxMessage: 'Name cannot be longer than {{ limit }} characters.'
    )]
    #[Groups([
        'get:item:category',
        'get:collection:category',
        'post:collection:category',
        'patch:item:category'
    ])]
    private ?string $name = null;


    /**
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    #[Assert\Length(
        max: 255,
        maxMessage: 'Description cannot be longer than {{ limit }} characters.'
    )]
    #[Groups([
        'get:item:category',
        'get:collection:category',
        'post:collection:category',
        'patch:item:category'
    ])]
    private ?string $description = null;

    /**
     * @var Collection<int, Product>
     */
    #[ORM\OneToMany(targetEntity: Product::class, mappedBy: 'category')]
    #[Assert\All([
        new Assert\Type(type: Product::class, message: 'Each product must be a valid Product object.')
    ])]
    #[Groups(['get:item:category'])]
    private Collection $products;

    /**
     *
     */
    public function __construct()
    {
        $this->products = new ArrayCollection();
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
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return $this
     */
    public function setDescription(string $description): static
    {
        $this->description = $description;

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
            $product->setCategory($this);
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
            if ($product->getCategory() === $this) {
                $product->setCategory(null);
            }
        }

        return $this;
    }

    /**
     * @return array
     */
    #[ArrayShape(['id' => "int|null", 'name' => "null|string", 'description' => "null|string"])] public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'description' => $this->getDescription(),
        ];
    }

}
