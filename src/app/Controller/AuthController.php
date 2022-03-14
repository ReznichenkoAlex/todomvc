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

		$_SESSION['id'] = $user->getId();
		$this->redirect('/');
    }

    public function login()
    {
		$name = trim($_POST['name']);

		if ($name) {
			$password = $_POST['password'];
			$user = UserModel::getByName($name);
			if (!$user) {
				$this->view->assign('error', 'Неверный логин и пароль');
			}

			if ($user) {
				if ($user->getPassword() != UserModel::getPasswordHash($password)) {
					$this->view->assign('error', 'Неверный логин и пароль');
				} else {
					$_SESSION['id'] = $user->getId();
					$this->redirect('/blog/index');
				}
			}
		}

		return $this->view->render('User/register.phtml', [
			'user' => UserModel::getById((int) $_GET['id'])
		]);
	}


	public function logoutAction()
	{
		session_destroy();

		$this->redirect('/user/login');
	}
}
