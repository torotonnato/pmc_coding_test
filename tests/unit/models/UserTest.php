<?php

namespace tests\unit\models;

use Yii;
use app\models\User;
use app\models\Role;

class UserTest extends \Codeception\Test\Unit
{
    static $tests = [
        [
            'id' => 1,
            'username' => 'admin',
            'password' => 'Admin777!',
            'role_id' => 0,
            'role' => 'admin',
            'auto_login' => false
        ],
        [
            'id' => 2,
            'username' => 'a_buyer',
            'password' => 'Buyer777!',
            'role_id' => 1,
            'role' => 'buyer',
            'auto_login' => true
        ],
        [
            'id' => 3,
            'username' => 'a_seller',
            'password' => 'Seller777!',
            'role_id' => 2,
            'role' => 'seller',
            'auto_login' => false
        ],
    ];

    public function testFindByUsernameAndTestRole()
    {
        foreach (self::$tests as $test) {
            $user = User::findByUsername($test['username']);
            verify($user)->notEmpty();
            verify($user->role_id)->equals($test['role_id']);
            verify($user->role->role)->equals($test['role']);
        }
    }

    public function testFindIdentity()
    {
        foreach (self::$tests as $test) {
            verify($user = User::findIdentity($test['id']))->notEmpty();
            verify($user->username)->equals($test['username']);
        }
    }

    public function testFindUserByAccessToken()
    {
        verify(true);
    }

    public function testValidate()
    {
        foreach (self::$tests as $test) {
            verify($user = User::findByUsername($test['username']))->notEmpty();
            $this->assertTrue($user->validateAuthKey(null));
            $this->assertTrue($user->validatePassword($test['password']));
        }
    }

    public function testLogin()
    {
        foreach (self::$tests as $test) {
            $user = User::login([
                'username' => $test['username'],
                'password' => $test['password'],
                'auto_login' => $test['auto_login']
            ]);
            $this->assertTrue($user != null);
            $this->assertTrue($user->auto_login === ($user->getAuthKey() != null));
            $this->assertFalse(Yii::$app->user->isGuest);
            $this->assertTrue(Yii::$app->user->id == $user->id);
            User::logout();
            $this->assertTrue(Yii::$app->user->isGuest);

            $user = User::login([
                'username' => $test['username'],
                'password' => 'WRONG PASSWORD',
                'auto_login' => $test['auto_login']
            ]);
            $this->assertTrue($user === null);
            $this->assertTrue(Yii::$app->user->isGuest);
            User::logout();
            $this->assertTrue(Yii::$app->user->isGuest);
        }
    }

    public function testSignup()
    {
        $test = [
            'username' => 'test',
            'password' => 'Test777!',
            'email' => 'test@email.org',
            'role' => Role::findByString('buyer'),
            'auto_login' => false
        ];
        $user = new User();
        $this->assertTrue(User::signup($test) != null);
        $this->assertFalse(Yii::$app->user->isGuest);
        User::logout();
        $this->assertTrue(Yii::$app->user->isGuest);

        $this->assertFalse(User::signup($test) != null);
        $this->assertFalse(Yii::$app->user->isGuest);
        User::logout();
        $this->assertTrue(Yii::$app->user->isGuest);
    }
}
