<?php

namespace Test\Product;


interface ProductInterface {

    /**
     * @param string $name
     * @param int $price
     */
    public function __construct($name, $price);

    /**
     * @return float|int
     */
    public function getPrice();

    /**
     * @param float|int $price
     * @return $this
     */
    public function changePrice($price);

    /**
     * @return string
     */
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