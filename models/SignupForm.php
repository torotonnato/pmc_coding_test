<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * SignupForm is the model behind the signup form.
 *
 * @property-read User|null $user
 *
 */
class SignupForm extends Model
{
    public $username;
    public $password;
    public $email;
    public $auto_login = true;
    public $role;
    public $verifyCode;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        $usernameParams = Yii::$app->params['username'];
        $passwordParams = Yii::$app->params['password']['strength'];
        return [
            // username, password and email are required
            [['username', 'password', 'email', 'role', 'auto_login'], 'required', 'message' => 'Please fill all fields to sign up'],

            // username rules
            ['username', 'trim'],
            ['username', 'string', 'min' => $usernameParams['minLen'], 'message' => 'Username too short'],
            ['username', 'string', 'max' => $usernameParams['maxLen'], 'message' => 'Username too long'],
            ['username', function ($attribute, $params, $validator) {
                $user = User::findByUsername($this->$attribute);
                if ($user != null) {
                    $this->addError($attribute, 'User already exists.');
                }
            }],

            // password has a min and a max len (not really needed) and must be secure
            // password min required strength params can be tweaked @config/params.php
            ['password', 'string', 'min' => $passwordParams['minLen'], 'message' => 'Password too short'],
            ['password', 'string', 'max' => $passwordParams['maxLen'], 'message' => 'Password too long'],
            ['password', validators\PasswordStrength::class],

            // email has to be trimmed, of appropriate length and valid
            ['email', 'trim'],
            ['email', 'string', 'max' => 32, 'message' => 'Email too long'],
            ['email', 'email', 'message' => 'Not a valid email address'],

            ['role', 'string'],
            
            // auto_login must be a boolean value
            ['auto_login', 'boolean'],

            ['verifyCode', 'captcha']
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'verifyCode' => 'Verification Code',
        ];
    }
    
    /**
     * Signs up a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
    public function signup()
    {
        if ($this->validate()) {
            return User::signup($this->attributes) != null;
        }
        return false;
    }
}
