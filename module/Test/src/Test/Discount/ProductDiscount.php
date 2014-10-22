<?php

namespace Test\Discount;

use Test\Product\ProductInterface;

class ProductDiscount implements DiscountInterface{

    private $type = self::PARTLY;

    /**
     * @var array|ProductInterface[]
     */
    private $products;
    /** @var float|int*/
    private $discount;
    /** @var array  */
    private $inUse = array();
    /** @var array  */
    private $tempUse = array();
    /**
     * @param ProductInterface $product
     * @return $this
     */
    public function addProduct(ProductInterface $product)
    {
        $this->products[] = $product;
        return $this;
    }

    /**
     * @param ProductInterface[] $products
     * @return $this
     */
    public function addProductSet($products)
    {
        $this->products[] = $products;
        return $this;
    }

    /**
     * @param $discount
     * @return $this
     */
    public function setDiscount($discount)
    {
        $this->discount = $discount;
        return $this;
    }

    /**
     * @param \Test\Product\ProductInterface[] $products
     * @return bool|mixed
     */
    public function prepare($products)
    {
        foreach($this->products as $key => $product){
            $productKey = $this->inList($product, $products);
            if(is_bool($productKey)){
                $this->tempUse = [];
                return false;
            }
            $this->tempUse[$productKey] = $productKey;
        }

        foreach ($this->tempUse as $item) {
            $this->inUse[$item] = $item;
        }

        $this->tempUse = [];
        $this->prepare($products);
        return true;
    }

    /**
     * @param ProductInterface|ProductInterface[] $product
     * @param ProductInterface[] $products
     * @return bool
     */
    private function inList($product, $products = array())
    {
        foreach ($products as $key => $item) {
            if($item->isChanged()){
                continue;
            }
            if(!isset($this->inUse[$key]) && !isset($this->tempUse[$key]) && $item->equals($product)){
                return $key;
            }
        }
        return false;
    }

    /**
     * @param ProductInterface[] $products
     * @return $this|void
     */
    public function useDiscount($products)
    {
        foreach($this->inUse as $key){
            $price = $products[$key]->getPrice();
            if($this->type == self::PARTLY){
                $price = $price - $price*$this->discount/100;
            }else{
                $price -= $this->discount;
            }
            $products[$key]->changePrice($price);
        }
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDiscount()
    {
        return $this->discount;
    }
}