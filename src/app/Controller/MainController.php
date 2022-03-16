<?php

namespace App\Controller;

use App\Base\Controller\AbstractController;

class MainController extends AbstractController
{
    public function index()
    {
		return $this->render('index.html.twig');
	}
}
