<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

/**
 * SellForm is the model behind the sell form.
 *
 **/
class SellForm extends Model
{
    public $image;
    public $model;
    public $year;
    public $price;
    public $km;

    public function rules()
    {
        $currentYear = intval(date("Y"));
        $carParams = Yii::$app->params['cars'];
        return [
            [['model', 'year', 'price', 'km'], 'required'],           
            ['model', 'match', 'pattern' => $carParams['modelRegex']],          
            ['year', 'integer', 'min' => $carParams['minYear'], 'max' => $currentYear],
            ['price', 'integer', 'min' => $carParams['minPrice'], 'max' => $carParams['maxPrice']],
            ['km', 'integer', 'min' => 0, 'max' => $carParams['maxKm']],
            [['image'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg, jpeg']
        ];
    }

    public function sell()
    {  
        if (Yii::$app->user->isGuest)
            return false;
        if ($this->validate()) {
            $imageBaseExt = $this->image->baseName . '.' . $this->image->extension;
            $imagePath = Yii::$app->params['uploadPath'] . $imageBaseExt;
            $this->image->saveAs($imagePath);
            $car = new Car();
            $car->setAttributes($this->attributes, true);   
            $car->image = Yii::$app->params['servePath'] . $imageBaseExt;
            return $car->sell(Yii::$app->user->id);
        }
        return false;
    }
}
