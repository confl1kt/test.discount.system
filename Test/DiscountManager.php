<?php

namespace Content\Service\Test;

use Content\Service\Test\Discount\DiscountInterface;
use Content\Service\Test\Order\OrderInterface;
use Content\Service\Test\Order\UseDiscountInterface;

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
            if($discount->prepare($order->getProduts())){
                $discount->useDiscount($order->getProducts());
            }
        }
        $order->setDiscounted();
    }
}