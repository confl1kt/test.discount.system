<?php

namespace Content\Service\Test\Order;


interface UseDiscountInterface {

    /**
     * @return bool
     */
    public function isDiscounted();

    /**
     * @param bool $discounted
     * @return $this
     */
    public function setDiscounted($discounted = true);
} 