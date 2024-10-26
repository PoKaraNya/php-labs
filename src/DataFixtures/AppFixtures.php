<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Entity\Category;
use App\Entity\Supplier;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;


class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        // Створення категорій
        $categories = [];
        for ($i = 0; $i < 5; $i++) {
            $category = new Category();
            $category->setName($faker->word);
            $category->setDescription($faker->sentence);
            $manager->persist($category);
            $categories[] = $category;
        }

        // Створення постачальників
        $suppliers = [];
        for ($i = 0; $i < 5; $i++) {
            $supplier = new Supplier();
            $supplier->setName($faker->company);
            $supplier->setContactName($faker->name);
            $supplier->setContactPhone($faker->phoneNumber);
            $supplier->setContactEmail($faker->email);
            $supplier->setAddress($faker->address);
            $manager->persist($supplier);
            $suppliers[] = $supplier;
        }

        // Створення продуктів
        for ($i = 0; $i < 20; $i++) {
            $product = new Product();
            $product->setName($faker->word);
            $product->setDescription($faker->sentence);
            $product->setPrice($faker->randomFloat(2, 10, 100));
            $product->setCategory($faker->randomElement($categories));
            $product->setSupplier($faker->randomElement($suppliers));
            $manager->persist($product);
        }

        $manager->flush();
    }
}
