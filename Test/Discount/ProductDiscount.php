<?php

namespace Content\Service\Test\Discount;

use Content\Service\Test\Product\ProductInterface;

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

    public function prepare($products)
    {
        foreach($this->products as $key => $product){
            if(!$this->inList($product, $products)){
                $this->inUse = [];
                return false;
            }
            $this->inUse[$key] = $key;
        }
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
            if(!isset($this->inUse[$key]) && $item->equals($product)){
                return true;
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