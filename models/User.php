<?php

namespace app\models;

use Yii;
use yii\base\InvalidArgumentException;
use yii\db\ActiveRecord;
use IntlChar;

class User extends ActiveRecord implements \yii\web\IdentityInterface
{  
    public static function tableName()
    {
        return '{{users}}';
    }

    public function rules()
    {
        $usernameParams = Yii::$app->params['username'];
        return [
            // username, password and email are required
            [['username', 'hash', 'email', 'role_id', 'auto_login'], 'required'],

            // username rules
            ['username', 'trim'],
            ['username', 'string', 'min' => $usernameParams['minLen'], 'message' => 'Username too short'],
            ['username', 'string', 'max' => $usernameParams['maxLen'], 'message' => 'Username too long'],

            // email has to be trimmed, of appropriate length and valid
            ['email', 'trim'],
            ['email', 'string', 'max' => 32, 'message' => 'Email too long'],
            ['email', 'email', 'message' => 'Not a valid email address'],

            ['role_id', 'integer'],
            
            // auto_login must be a boolean value
            ['auto_login', 'boolean']
        ];
    }

    public static function findIdentity($id)
    {
        return self::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null;
    }

    public static function findByUsername($username)
    {
        return User::findOne(['username' => $username]);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }


    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    public function manageAuthKey()
    {
        if ($this->auto_login) {
            $auth_key_len = Yii::$app->params['authKeyLen'];
            $this->auth_key = \Yii::$app->security->generateRandomString($auth_key_len);
        }else {
            $this->auth_key = null;
        }
    }

    public function getRole()
    {
        return $this->hasOne(Role::class, ['id' => 'role_id']);
    }

    public function validatePassword($password)
    {
        try {
            $isValid = Yii::$app->getSecurity()->validatePassword($password, $this->hash);
        } catch (InvalidArgumentException $e) {
            error_log("Wrong or malicious authentication attempt with user:{$this->username}, pwd:{$password}");
            return false;
        }
        return $isValid;
    }

    public static function login($arrayData)
    {
        $user = self::findByUsername($arrayData['username']);
        if ($user && $user->validatePassword($arrayData['password'])) {
            $user->auto_login = $arrayData['auto_login'];
            $user->manageAuthKey();
            $user->save();
            Yii::$app->user->login($user, $user->auto_login ? Yii::$app->params['auto_login_expiration'] : 0);
            return $user;
        }
        return null;
    }

    public static function signup($arrayData)
    {
        $cost = Yii::$app->params['password']['hashCost'];
        $hash = Yii::$app->getSecurity()->generatePasswordHash($arrayData['password'], $cost);
        $user = new User();
        $user->username = $arrayData['username'];
        $user->hash = $hash;
        $user->email = $arrayData['email'];
        $user->manageAuthKey();
        $user->role_id = Role::findByString($arrayData['role'])->id;
        $user->auto_login = $arrayData['auto_login'];
        $ret = $user->save();
        Yii::$app->user->login($user, $user->auto_login ? Yii::$app->params['auto_login_expiration'] : 0);
        if ($ret)
            return $user;
        return null;
    }

    public static function logout()
    {
        Yii::$app->user->logout();
    }
}
