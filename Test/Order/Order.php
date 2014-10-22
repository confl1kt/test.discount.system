<?php
namespace Test\Order;

use Test\Product\ProductInterface;

class Order implements OrderInterface, UseDiscountInterface{

    /** @var ProductInterface[] */
    private $products = array();
    /** @var bool  */
    private $discounted = false;

    /**
     * @param ProductInterface $product
     * @return $this
     */
    public function addProduct(ProductInterface $product)
    {
        $this->products[] = $product;
    }

    /**
     * @param ProductInterface[] $products
     * @return mixed
     */
    public function addProducts($products = array())
    {
        foreach($products as $product){
            $this->addProduct($product);
        }
    }

    /**
     * @return ProductInterface[]
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * @param ProductInterface[] $products
     * @return $this
     */
    public function removeProducts($products = array())
    {
        foreach ($products as $product) {
            $this->removeProduct($product);
        }

    }

    /**
     * @param ProductInterface $product
     * @return $this
     */
    public function removeProduct(ProductInterface $product)
    {
        foreach($this->products as $key => $item){
            if($product->equals($item)){
                unset($this->products[$key]);
            }
        }
    }

    /**
     * @return bool
     */
    public function isDiscounted()
    {
        return $this->discounted;
    }

    /**
     * @param bool $discounted
     * @return $this
     */
    public function setDiscounted($discounted = true)
    {
        $this->discounted = $discounted;
        return $this;
    }
}