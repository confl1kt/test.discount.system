<?php
namespace Test\Discount;

use Test\Product\ProductInterface;

class ItemCountDiscount implements DiscountInterface{

    private $type = self::PARTLY;

    /** @var int  */
    private $discount = 0;
    /** @var ProductInterface[] */
    private $exceptions = array();
    /** @var int */
    private $count = PHP_INT_MAX;
    /** @var array  */
    private $inUse = array();

    /**
     * @param float|int $discount
     * @return $this
     */
    public function setDiscount($discount)
    {
        $this->discount = $discount;
        return $this;
    }

    /**
     * @param int $count
     * @return $this
     */
    public function setItemCount($count)
    {
        $this->count = $count;
        return $this;
    }

    public function addException(ProductInterface $product)
    {
        $this->exceptions[] = $product;
    }

    /**
     * @param ProductInterface[] $products
     * @return mixed
     */
    public function prepare($products)
    {
        $count = $this->getItemCount($products);
        if($count >= $this->count){
            $this->inUse = [];
            return false;
        }
        $this->inUse = [];
        return true;
    }

    /**
     * @param ProductInterface[] $products
     * @return int
     */
    private function getItemCount($products){
        $count = 0;
        foreach($products as $key => $product){
            if($product->isChanged()){
                continue;
            }
            if(!$product->equals($this->exceptions)){
                $this->inUse[$key] = $key;
                $count++;
            }
        }
        return $count;
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
                $price = $price - $price * $this->discount / 100;
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