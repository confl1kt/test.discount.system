<?php
namespace Content\Service\Test\Discount;

use Content\Service\Test\Product\ProductInterface;

interface DiscountInterface {
    const CONSTANTLY = 1;
    const PARTLY = 2;
    /**
     * @param $discount
     * @return $this
     */
    public function setDiscount($discount);

    /**
     * @return mixed
     */
    public function getDiscount();

    /**
     * @param ProductInterface[] $products
     * @return mixed
     */
    public function prepare($products);

    /**
     * @param ProductInterface[] $products
     * @return $this
     */
    public function useDiscount($products);
}