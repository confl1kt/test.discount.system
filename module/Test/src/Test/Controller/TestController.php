<?php

namespace Test\Controller;


use Test\Order\Order;
use Test\Product\Product;
use Zend\Http\Response;
use Zend\Mvc\Controller\AbstractActionController;

class TestController extends AbstractActionController{

    public function indexAction()
    {
        /** @var \Test\PriceCalculator $service */
        $service = $this->getServiceLocator()->get('Calculator');
        $order = new Order();
        $order->addProduct(new Product('A', 100));
        $order->addProduct(new Product('B', 100));

        $order->addProduct(new Product('A', 100));
        $order->addProduct(new Product('B', 100));

        $order->addProduct(new Product('A', 100));
        $order->addProduct(new Product('M', 100));

        $order->addProduct(new Product('R', 100));
        $order->addProduct(new Product('R', 100));
        $order->addProduct(new Product('R', 100));
        $order->addProduct(new Product('R', 100));
        $order->addProduct(new Product('R', 100));
        $order->addProduct(new Product('R', 100));

        $order->addProduct(new Product('E', 100));
        $order->addProduct(new Product('D', 100));
        $service->setOrder($order);
        $this->response->setContent($service->calculate());
        return $this->response;
    }

} 