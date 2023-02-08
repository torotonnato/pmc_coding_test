<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\validators\RegularExpressionValidator;

class Car extends ActiveRecord {

    public static function tableName()
    {
        return '{{market}}';
    }

    public function rules()
    {
        $currentYear = intval(date("Y"));
        $carParams = Yii::$app->params['cars'];
        return [
            [['image', 'model', 'year', 'price', 'km', 'seller_id'], 'required'],
            ['model', 'match', 'pattern' => $carParams['modelRegex']],          
            ['year', 'integer', 'min' => $carParams['minYear'], 'max' => $currentYear],
            ['price', 'integer', 'min' => $carParams['minPrice'], 'max' => $carParams['maxPrice']],
            ['km', 'integer', 'min' => 0, 'max' => $carParams['maxKm']],
            ['seller_id', 'integer'],
            ['seller_id', function ($attribute, $params, $validator) {               
                $user = User::findIdentity($this->seller_id);
                if (!$user || ($user->role->role != 'seller')) {
                    $this->addError($attribute, 'User is not a seller.');
                }
            }],
        ];
    }

    public static function findById($id)
    {
        return self::findOne($id);
    }

    public static function findByModel($model)
    {
        return self::findOne(['model' => $model]);
    }

    public function getSeller()
    {
        return $this->hasOne(User::class, ['id' => 'seller_id']);
    }

    public function sell($seller_id = null)
    {
        if ($seller_id != null) {
            $this->seller_id = $seller_id;
        }
        $ret = $this->save();
        return $ret;
    }

    public function buy($buyer_id)
    {
        $order = new Order([
            'seller_id' => $this->seller_id,
            'buyer_id' => $buyer_id,
            'car_id' => $this->id
        ]);
        return $order && $order->save();
    }

}