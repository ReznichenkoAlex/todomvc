<?php

namespace App\Controller;

use App\Base\Controller\AbstractController;

class MainController extends AbstractController
{
	public function index()
	{
		if ($this->isUserSet()) {
			return $this->render('index.html.twig');
		}
		$this->redirect('/user/login');
	}
}
