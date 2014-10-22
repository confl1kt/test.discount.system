<?php

namespace Test;

use Test\Discount\DiscountInterface;
use Test\Order\OrderInterface;
use Test\Order\UseDiscountInterface;

class DiscountManager {
    /** @var DiscountInterface[] */
    private $discounts;

    public function addProductDiscount(DiscountInterface $discount)
    {
        $this->discounts[] = $discount;
    }

    /**
     * @param OrderInterface|UseDiscountInterface $order
     */
    public function useDiscountsToOrder(OrderInterface $order)
    {
        if(!($order instanceof UseDiscountInterface)){
            return;
        }
        if($order->isDiscounted()){
            return;
        }
        foreach($this->discounts as $discount){
            if($discount->prepare($order->getProducts())){
                $discount->useDiscount($order->getProducts());
            }
        }
        $order->setDiscounted();
    }
}