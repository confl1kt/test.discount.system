<?php

namespace Content\Service\Test\Product;


interface ProductInterface {

    public function __construct($name, $price);

    public function getPrice();

    public function changePrice($price);

    public function getName();

    /**
     * @param ProductInterface|ProductInterface[] $products
     * @return bool
     */
    public function equals($products);

    /**
     * @return bool
     */
    public function isChanged();
}