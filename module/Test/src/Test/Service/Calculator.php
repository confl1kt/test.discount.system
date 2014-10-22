<?php

namespace Test\Service;


use Test\Discount\DiscountChain;
use Test\Discount\ItemCountDiscount;
use Test\Discount\ProductDiscount;
use Test\DiscountManager;
use Test\PriceCalculator;
use Test\Product\Product;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class Calculator implements FactoryInterface{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $discountManager = new DiscountManager();
        $calculator = new PriceCalculator();
        $calculator->setDiscountManager($discountManager);
        $this->addDiscounts($discountManager);

        return $calculator;
    }

    private function addDiscounts(DiscountManager $discountManager)
    {
        $productDiscount = new ProductDiscount();
        $productDiscount->addProduct(new Product('A'));
        $productDiscount->addProduct(new Product('B'));
        $productDiscount->setDiscount(10);
        $discountManager->addProductDiscount($productDiscount);

        $productDiscount = new ProductDiscount();
        $productDiscount->addProduct(new Product('D'));
        $productDiscount->addProduct(new Product('E'));
        $productDiscount->setDiscount(5);
        $discountManager->addProductDiscount($productDiscount);

        $productDiscount = new ProductDiscount();
        $productDiscount->addProduct(new Product('E'));
        $productDiscount->addProduct(new Product('F'));
        $productDiscount->addProduct(new Product('G'));
        $productDiscount->setDiscount(5);
        $discountManager->addProductDiscount($productDiscount);

        $productDiscount = new ProductDiscount();
        $productDiscount->addProduct(new Product('A'));
        $productDiscount->addProductSet([
            new Product('K'),
            new Product('L'),
            new Product('M')
        ]);
        $productDiscount->setDiscount(5);
        $discountManager->addProductDiscount($productDiscount);

        $this->addChain($discountManager);
    }

    private function addChain(DiscountManager $discountManager)
    {
        $productDiscount = new DiscountChain();
        $itemCountDiscount = new ItemCountDiscount();
        $itemCountDiscount->addException(new Product('A'));
        $itemCountDiscount->addException(new Product('C'));
        $itemCountDiscount->setDiscount(5);
        $itemCountDiscount->setItemCount(3);
        $productDiscount->addDiscount($itemCountDiscount, 1);

        $itemCountDiscount = new ItemCountDiscount();
        $itemCountDiscount->addException(new Product('A'));
        $itemCountDiscount->addException(new Product('C'));
        $itemCountDiscount->setDiscount(10);
        $itemCountDiscount->setItemCount(4);
        $productDiscount->addDiscount($itemCountDiscount, 2);

        $itemCountDiscount = new ItemCountDiscount();
        $itemCountDiscount->addException(new Product('A'));
        $itemCountDiscount->addException(new Product('C'));
        $itemCountDiscount->setDiscount(20);
        $itemCountDiscount->setItemCount(5);
        $productDiscount->addDiscount($itemCountDiscount, 3);

        $discountManager->addProductDiscount($productDiscount);
    }
}