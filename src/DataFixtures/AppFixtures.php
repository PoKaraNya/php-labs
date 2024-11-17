<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use App\Entity\Inventory;
use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\Product;
use App\Entity\Category;
use App\Entity\PurchaseOrder;
use App\Entity\PurchaseOrderItem;
use App\Entity\Shipment;
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
        for ($i = 0; $i < 20; $i++) {
            $category = new Category();
            $category->setName($faker->word);
            $category->setDescription($faker->sentence);
            $manager->persist($category);
            $categories[] = $category;
        }

        // Створення постачальників
        $suppliers = [];
        for ($i = 0; $i < 10; $i++) {
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
        $products = [];
        for ($i = 0; $i < 100; $i++) {
            $product = new Product();
            $product->setName($faker->word);
            $product->setDescription($faker->sentence);
            $product->setPrice($faker->randomFloat(2, 10, 100));
            $product->setCategory($faker->randomElement($categories));
            $product->setSupplier($faker->randomElement($suppliers));
            $manager->persist($product);
            $products[] = $product;
        }

        // Створення інвентарю
        foreach ($products as $product) {
            $inventory = new Inventory();
            $inventory->setProduct($product);
            $inventory->setQuantity($faker->numberBetween(10, 100));
            $inventory->setLastUpdated($faker->dateTimeThisYear);
            $manager->persist($inventory);
        }

        // Створення клієнтів
        $customers = [];
        for ($i = 0; $i < 10; $i++) {
            $customer = new Customer();
            $customer->setName($faker->name);
            $customer->setEmail($faker->email);
            $customer->setPhone($faker->phoneNumber);
            $customer->setAddress($faker->address);
            $manager->persist($customer);
            $customers[] = $customer;
        }

        // Створення замовлень
        $orders = [];
        for ($i = 0; $i < 15; $i++) {
            $order = new Order();
            $order->setCustomer($faker->randomElement($customers));
            $order->setOrderDate($faker->dateTimeThisYear);
            $order->setStatus($faker->randomElement(['new', 'processing', 'completed', 'cancelled']));
            $order->setTotalAmount($faker->randomFloat(2, 20, 500));
            $manager->persist($order);
            $orders[] = $order;
        }

        // Створення елементів замовлення
        foreach ($orders as $order) {
            $numItems = $faker->numberBetween(1, 5);
            for ($j = 0; $j < $numItems; $j++) {
                $orderItem = new OrderItem();
                $product = $faker->randomElement($products);
                $quantity = $faker->numberBetween(1, 5);
                $orderItem->setOrderId($order);
                $orderItem->setProduct($product);
                $orderItem->setQuantity($quantity);
                $orderItem->setPricePerUnit($product->getPrice());
                $manager->persist($orderItem);
            }
        }

        // Створення відправлень
        foreach ($orders as $order) {
            if ($order->getStatus() === 'completed') {
                $shipment = new Shipment();
                $shipment->setOrderId($order);
                $shipment->setShipmentDate($faker->dateTimeThisYear);
                $shipment->setDeliveryDate($faker->dateTimeThisYear);
                $shipment->setStatus($faker->randomElement(['in transit', 'delivered', 'delayed']));
                $manager->persist($shipment);
            }
        }

        // Створення закупівельних замовлень
        $purchaseOrders = [];
        for ($i = 0; $i < 10; $i++) {
            $purchaseOrder = new PurchaseOrder();
            $purchaseOrder->setSupplier($faker->randomElement($suppliers));
            $purchaseOrder->setOrderDate($faker->dateTimeThisYear);
            $purchaseOrder->setStatus($faker->randomElement(['ordered', 'received', 'cancelled']));
            $purchaseOrder->setTotalCost($faker->randomFloat(2, 100, 1000));
            $manager->persist($purchaseOrder);
            $purchaseOrders[] = $purchaseOrder;
        }

        // Створення елементів закупівельних замовлень
        foreach ($purchaseOrders as $purchaseOrder) {
            for ($j = 0; $j < mt_rand(1, 5); $j++) {
                $purchaseOrderItem = new PurchaseOrderItem();
                $purchaseOrderItem->setPurchaseOrder($purchaseOrder);
                $purchaseOrderItem->setProduct($faker->randomElement($products));
                $purchaseOrderItem->setQuantity($faker->numberBetween(10, 50));
                $purchaseOrderItem->setPricePerUnit($faker->randomFloat(2, 5, 50));
                $purchaseOrderItem->setTotalPrice($purchaseOrderItem->getQuantity() * $purchaseOrderItem->getPricePerUnit());
                $manager->persist($purchaseOrderItem);
            }
        }

        $manager->flush();
    }
}
