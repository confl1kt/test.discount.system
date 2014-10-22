<?php

namespace Test\Discount;


use Test\Product\ProductInterface;

class DiscountChain implements DiscountInterface{

    /** @var DiscountInterface[] */
    private $discounts = array();
    /** @var int */
    private $discount = 0;
    /** @var DiscountInterface */
    private $finalDiscount;
    /** @var array  */
    private $priorities = array();

    /**
     * @param DiscountInterface $discount
     * @param int $priority
     */
    public function addDiscount(DiscountInterface $discount, $priority)
    {
        $this->discounts[] = $discount;
        end($this->discounts);
        $key = key($this->discounts);
        $this->priorities[$key] = $priority;
    }

    /**
     * @param $discount
     * @return $this
     */
    public function setDiscount($discount)
    {
        $this->discount = $discount;
    }

    /**
     * @param ProductInterface[] $products
     * @return mixed
     */
    public function prepare($products)
    {
        uasort($this->priorities,function($a,$b){
            if($a == $b) return 0;
            return ($a < $b) ? -1 : 1;
        });
        foreach($this->priorities as $key => $item){
            if($this->discounts[$key]->prepare($products)){
                $this->finalDiscount = $this->discounts[$key];
                return true;
            }
        }
        return false;
    }

    /**
     * @return mixed
     */
    public function getDiscount()
    {
        return $this->discount;
    }

    /**
     * @param ProductInterface[] $products
     * @return $this
     */
    public function useDiscount($products)
    {
        $this->finalDiscount->useDiscount($products);
        return $this;
    }
}