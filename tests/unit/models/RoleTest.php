<?php

namespace tests\unit\models;

use app\models\Role;

class RoleTest extends \Codeception\Test\Unit
{
    public function testRole()
    {
        $roles = new Role();
        $expected = [
            0 => 'admin',
            1 => 'buyer',
            2 => 'seller'
        ];
        verify($roles->getAll() == $expected);
    }

    public function testHardcoded()
    {
        $actual = Role::findByString('admin');
        verify($actual->id)->equals(0);
        $actual = Role::findByString('buyer');
        verify($actual->id)->equals(1);
        $actual = Role::findByString('seller');
        verify($actual->id)->equals(2);
    }
}