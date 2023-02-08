<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class Order extends ActiveRecord
{  
    public static function tableName()
    {
        return '{{orders}}';
    }

    public function rules()
    {
        return [
            [['seller_id', 'buyer_id'], 'required'],
            ['seller_id', 'integer'],
            ['buyer_id', 'integer']
        ];
    }

    public function getCar()
    {
        return $this->hasOne(Car::class, ['id' => 'car_id']);
    }

    public function getSeller()
    {
        return $this->hasOne(User::class, ['id' => 'seller_id']);
    }

    public function getBuyer()
    {
        return $this->hasOne(User::class, ['id' => 'buyer_id']);
    }

    public static function findByCarId($id)
    {
        return self::find()->where(['car_id' => $id])->all();
    }

    public static function findById($id)
    {
        return self::findOne($id);
    }

    public static function listMarketCars()
    {
        $subQuery = Order::find()->select('car_id');
        return Car::find()
            ->where(['not in', 'id', $subQuery])
            ->all();
        return $query;
    }

    public static function listSellerCars($seller_id)
    {
        return Car::findAll(['seller_id' => $seller_id]);
    }

    public static function listBuyerCars($buyer_id)
    {
        return self::findAll(['buyer_id' => $buyer_id]);
    }
}
