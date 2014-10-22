<?php

namespace Test\Product;


class Product implements ProductInterface
{

    private $name;
    private $price;

    private $changed = false;

    public function __construct($name, $price = 0)
    {
        $this->name = $name;
        $this->price = $price;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function changePrice($price)
    {
        $this->price = $price;
        $this->changed = true;
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    /**
     * @param ProductInterface|ProductInterface[] $products
     * @return bool
     */
    public function equals($products)
    {
        if (!is_array($products)) {
            if($products instanceof ProductInterface){
                return $products->getName() === $this->name;
            }
            return false;
        }
        foreach ($products as $item){
            if ($item->getName() == $this->name) {
                return true;
            }
        }
        return false;
    }

    public function isChanged()
    {
        return true;
    }
}