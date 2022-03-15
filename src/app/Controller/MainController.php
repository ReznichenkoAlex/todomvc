<?php

namespace App\Controller;

use App\Base\Controller\AbstractController;

class MainController extends AbstractController
{
    public function index()
    {
		if(!$this->user){
			$this->redirect('/user/register');
		}
		return $this->render('index.html.twig');
	}
}
