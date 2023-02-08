<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property-read User|null $user
 *
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $auto_login = true;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        $usernameParams = Yii::$app->params['username'];
        $passwordParams = Yii::$app->params['password']['strength'];
        return [
            // username and password are both required
            [['username', 'password'], 'required'],

            // username rules
            ['username', 'trim'],
            ['username', 'string', 'min' => $usernameParams['minLen'], 'message' => 'Username too short'],
            ['username', 'string', 'max' => $usernameParams['maxLen'], 'message' => 'Username too long'],

            // password has a min and a max len (not really needed) and must be secure
            // password has a max len and is validated by validatePassword()
            ['password', 'string', 'min' => $passwordParams['minLen'], 'message' => 'Password too short'],
            ['password', 'string', 'max' => $passwordParams['maxLen'], 'message' => 'Password too long'],
            ['password', 'validatePassword'],

            // auto_login must be a boolean value
            ['auto_login', 'boolean']
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        return User::login($this->attributes) != null;
    }
}
