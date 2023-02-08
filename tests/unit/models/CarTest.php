<?php

namespace tests\unit\models;

use Yii;
use app\models\User;
use app\models\Car;
use app\models\Order;

class CarTest extends \Codeception\Test\Unit
{  
    static $testCars = [
        [
            'id' => null,
            'model' => 'Acura MDX',
            'year' => 2023,
            'price' => 50745,
            'km' => 10,
            'seller_id' => 3,
            'image' => 'acura.jpg'
        ],
        [
            'id' => null,
            'model' => 'Alfa Romeo Giulia Quadrifoglio',
            'year' => 2023,
            'price' => 81855,
            'km' => 20,
            'seller_id' => 3,
            'image' => 'alfa.png'
        ]
    ];

    public function makeDefaultTestCar()
    {
        $defaultCar = [
            'model' => 'UNIT TEST CAR',
            'year' => Yii::$app->params['cars']['minYear'],
            'price' => Yii::$app->params['cars']['minPrice'],
            'km' => 0,
            'seller_id' => User::findByUsername('a_seller')->id,
            'image' => 'test_car.png'
        ];
        $car = new Car();        
        $car->setAttributes($defaultCar, true);
        $this->assertTrue($car->validate());
        return $car;
    }

    public function testValidators()
    {
        $carParams = Yii::$app->params['cars'];
        $testCars = [
            // Wrong model name
            [
                'model' => 'Weird M_0-d+3!l',
                'year' => $carParams['minYear'],
                'price' => $carParams['minPrice'],
                'km' => $carParams['maxKm'],
                'seller_id' => User::findByUsername('a_seller'),
            ],
            // Wrong year (past)
            [
                'model' => 'A car',
                'year' => $carParams['minYear'] - 1,
                'price' => $carParams['minPrice'],
                'km' => $carParams['maxKm'],
                'seller_id' => User::findByUsername('a_seller')
            ],

            // Wrong year (future)
            [
                'model' => 'A car',
                'year' => intval(date("Y")) + 1,
                'price' => $carParams['minPrice'],
                'km' => $carParams['maxKm'],
                'seller_id' => User::findByUsername('a_seller')
            ],
            // Wrong price (zero or less than zero)
            [
                'model' => 'A car',
                'year' => $carParams['minYear'],
                'price' => 0,
                'km' => $carParams['maxKm'],
                'seller_id' => User::findByUsername('a_seller')
            ],
            // Wrong price (too much)
            [
                'model' => 'A car',
                'year' => $carParams['minYear'],
                'price' => $carParams['maxPrice'] + 1,
                'km' => $carParams['maxKm'],
                'seller_id' => User::findByUsername('a_seller')
            ],            
            // Wrong KMs (zero or less than zero)
            [
                'model' => 'A car',
                'year' => $carParams['minYear'],
                'price' => $carParams['minPrice'],
                'km' => -1,
                'seller_id' => User::findByUsername('a_seller')
            ],
            // Wrong KMs (too much)
            [
                'model' => 'A car',
                'year' => $carParams['minYear'],
                'price' => $carParams['minPrice'],
                'km' => $carParams['maxKm'] + 1,
                'seller_id' => User::findByUsername('a_seller')
            ],
            // Wrong seller_id (a_buyer)
            [
                'model' => 'A car',
                'year' => $carParams['minYear'],
                'price' => $carParams['minPrice'],
                'km' => $carParams['maxKm'] + 1,
                'seller_id' => User::findByUsername('a_buyer')            
            ]           
        ];
        foreach ($testCars as $data) {
            $car = new Car();
            $car->setAttributes($data, true);
            $this->assertFalse($car->validate());
        }
    }

    public function testHarcoded()
    {
        foreach (self::$testCars as $data) {
                $car = Car::findByModel($data['model']);
                $this->assertTrue($car != null);
                $car->id = null;
                verify($car->attributes)->equals($data);   
        }
    }

    public function testSell()
    {       
        $car = $this->makeDefaultTestCar();       
        $this->assertTrue($car->sell());
        $actual = Car::findByModel($car->model);
        verify($actual->attributes)->equals($car->attributes);
    }

    public function testBuy()
    {       
        $car = $this->makeDefaultTestCar(); 
        $this->assertTrue($car->sell(User::findByUsername('a_seller')->id));
        $this->assertTrue($car->buy(User::findByUsername('a_buyer')->id));
        $orders = Order::findByCarId($car->id);
        $this->assertTrue(count($orders) == 1);
        verify($orders[0]->car->attributes)->equals($car->attributes);
    }

}