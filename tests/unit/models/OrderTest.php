<?php

namespace tests\unit\models;

use app\models\User;
use app\models\Car;
use app\models\Order;

class OrderTest extends \Codeception\Test\Unit
{

    public function testOrder()
    {
        $test = [
            'seller_id' => User::findByUsername('a_seller')->id,
            'buyer_id' => User::findByUsername('a_buyer')->id,
            'car_id' => Car::findByModel('Acura MDX')->id
        ];

        $oldBoughtNum = count(Order::listBuyerCars($test['buyer_id']));
        $oldSoldNum = count(Order::listSellerCars($test['seller_id']));

        $order = new Order($test);
        $order->save();
        $actual = Order::findById($order->id);

        verify($actual->attributes)->equals($order->attributes);
        verify($actual->seller->attributes)->equals($order->seller->attributes);
        verify($actual->buyer->attributes)->equals($order->buyer->attributes);

        $newBoughtNum = count(Order::listBuyerCars($test['buyer_id']));
        $newSoldNum = count(Order::listSellerCars($test['seller_id']));

        verify($newBoughtNum)->equals($oldBoughtNum + 1);
        verify($newSoldNum)->equals($oldSoldNum + 1);
    }

}