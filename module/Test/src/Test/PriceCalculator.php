<?php

namespace Test;


use Test\Order\OrderInterface;
use Test\Order\UseDiscountInterface;

class PriceCalculator {
    /** @var DiscountManager */
    private $discountManager = null;
    /** @var OrderInterface|UseDiscountInterface */
    private $order;

    /**
     * @param DiscountManager $discountManager
     * @return $this
     */
    public function setDiscountManager(DiscountManager $discountManager)
    {
        $this->discountManager = $discountManager;
        return $this;
    }

    /**
     * @param OrderInterface $order
     * @return $this
     */
    public function setOrder(OrderInterface $order)
    {
        $this->order = $order;
        return $this;
    }

    public function calculate()
    {
        $this->useDiscount();
        $price = 0;
        foreach($this->order->getProducts() as $product){
            $price += $product->getPrice();
        }
        return $price;
    }

    private function useDiscount()
    {
        if(!is_null($this->discountManager)){
            $this->discountManager->useDiscountsToOrder($this->order);
        }
    }
} 