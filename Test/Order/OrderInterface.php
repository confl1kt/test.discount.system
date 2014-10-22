<?php
namespace Content\Service\Test\Order;

use Content\Service\Test\Product\ProductInterface;

interface OrderInterface {
    /**
     * @param ProductInterface $product
     * @return $this
     */
    public function addProduct(ProductInterface $product);

    /**
     * @param ProductInterface[] $products
     * @return mixed
     */
    public function addProducts($products = array());

    /**
     * @return ProductInterface[]
     */
    public function getProducts();

    /**
     * @param ProductInterface[] $products
     * @return $this
     */
    public function removeProducts($products = array());

    /**
     * @param ProductInterface $product
     * @return $this
     */
    public function removeProduct(ProductInterface $product);
}