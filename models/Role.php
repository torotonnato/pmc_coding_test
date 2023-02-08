<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class Role extends ActiveRecord
{  
    public static function tableName()
    {
        return '{{roles}}';
    }

    public static function findByString($role)
    {
        return Role::findOne(['role' => $role]);
    }

    public static function getAll()
    {
        return self::find()->asArray()->all();
    }
}
