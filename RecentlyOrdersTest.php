<?php

namespace XLiteWeb\tests;

use Facebook\WebDriver\Remote\RemoteWebDriver;
use XLiteWeb\CheckoutTrait;

/**
 * @author cerber
 */
class testRecentlyOrders extends \XLiteWeb\AXLiteWeb
{
    use CheckoutTrait;

    public function testCounterAfterCreateAndRemoveOrder()
    {
        $adminOrders = $this->AdminOrders;
        $adminOrders->load(true);

        $beforeCounter = $adminOrders->getRecentlyOrdersCounter();

        $orderNumber = $this->placeOrder(37);

        $adminOrders->load();
        $afterCreateCounter = $adminOrders->getRecentlyOrdersCounter();

        $this->assertEquals($beforeCounter+1, $afterCreateCounter, 'Error validation Awaiting processing.');

        $adminOrders->removeOrderByOrderNumber($orderNumber);

        $afterRemoveCounter = $adminOrders->getRecentlyOrdersCounter();

        $this->assertEquals($afterCreateCounter-1, $afterRemoveCounter, 'Error validation Awaiting processing.');
    }

    public function testCounterAfterChangeStatusOnOrderPage()
    {
        $orderNumber = $this->placeOrder(37);

        $adminOrder = $this->AdminOrder;
        $adminOrder->load(true,$orderNumber);

        $beforeCounter = $adminOrder->getRecentlyOrdersCounter();

        $adminOrder->selectStatus(true,'payment');
        $adminOrder->saveChanges();

        $afterCounter = $adminOrder->getRecentlyOrdersCounter();

        $this->assertEquals($beforeCounter-1, $afterCounter, 'Error validation Awaiting processing.');

    }

    public function testCounterAfterChangeStatusOnOrdersPage()
    {
        $orderNumber = $this->placeOrder(37);

        $adminOrders = $this->AdminOrders;
        $adminOrders->load(true);

        $beforeCounter = $adminOrders->getRecentlyOrdersCounter();
        $adminOrders->setPaymentStatusByOrderNumber($orderNumber,'Paid');
        $adminOrders->saveChanges();

        $afterCounter = $adminOrders->getRecentlyOrdersCounter();

        $this->assertEquals($beforeCounter-1, $afterCounter, 'Error validation Awaiting processing.');

    }
}
