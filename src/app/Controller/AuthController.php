<?php

namespace App\Controller;

use App\Base\Controller\AbstractController;
use App\Model\User;
use Exception;
use Doctrine\ORM\EntityManagerInterface;

class AuthController extends AbstractController
{
	public function register()
	{

		$validForm = $this->validForm();

		if ($validForm['status']) {
			try {
				$this->createUser();
				$this->redirect('/');
			} catch (Exception $e) {
				return $e->getMessage();
			}
		} else {
			return $this->render('register.html.twig', [
				'error' => $validForm['message']
			]);
		}
	}

	public function login()
	{
		$validForm = $this->validForm(true);

		if ($validForm['status']) {
			$user     = $this->getUser();
			$password = $_POST['password'];
			if ($user->getPassword() !== $this->hashPassword($password)) {
				$validForm['message'] = 'Неверная почта или пароль';
			} else {
				$_SESSION['id'] = $user->getId();
				$this->redirect('/');
			}
		}

		return $this->render('register.html.twig', [
			'error' => $validForm['message']
		]);

	}


	public function logoutAction()
	{
		session_destroy();

		$this->redirect('/user/login');
	}

	private function hashPassword(string $password): string
	{
		return sha1('asdfa' . $password);
	}

	private function validForm($loginForm = false): array
	{
		$error   = '';
		$success = true;

		if (empty($_POST['email'])) {
			$error   = 'Почта не может быть пустой';
			$success = false;
		}

		if (empty($_POST['password'])) {
			$error   = 'Пароль не может быть пустым';
			$success = false;
		}

		if (!empty($_POST['email']) && !empty($_POST['password'])) {
			$user = $this->getUser();
			if ($loginForm) {
				if (!$user) {
					$error   = 'Неверная почта или пароль';
					$success = false;
				}
			} else {
				if ($user) {
					$error   = 'Пользователь с такой почтой уже существует';
					$success = false;
				}
			}
		}

		if (empty($_POST['email']) && empty($_POST['password'])) {
			$error   = '';
			$success = false;
		}

		return ['message' => $error, 'status' => $success];
	}

	private function createUser()
	{
		$email    = trim($_POST['email']);
		$password = trim($_POST['password']);

		$em = $this->getDoctrine();
		/** @var EntityManagerInterface $em */

		$user = (new User())
			->setEmail($email)
			->setPassword($this->hashPassword($password));

		$em->persist($user);
		$em->flush();

		$_SESSION['id'] = $user->getId();
	}

	private function getUser(): ?User
	{
		$email = $_POST['email'];
		$em    = $this->getDoctrine();
		/** @var EntityManagerInterface $em */
		return $em->getRepository(User::class)->findOneBy(['email' => $email]);
	}
}
