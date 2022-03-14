<?php

namespace App\Controller;

use App\Base\Controller\AbstractController;
use App\Model\User;

class AuthController extends AbstractController
{
    public function register()
    {
        $name = 'test_name' . rand(1,100);
		$password = 'QWE123rty';

		$user = new User();
		$user
			->setName($name)
			->setPassword(User::getPasswordHash($password));

		try{
			$user->save();
		} catch (\PDOException $e) {
			return $e->getMessage();
		}

		return $user->getId();
    }

    public function login()
    {
        echo __METHOD__;
    }
}
