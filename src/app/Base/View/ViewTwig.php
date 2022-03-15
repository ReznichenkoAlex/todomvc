<?php

namespace App\Base\View;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class ViewTwig implements ViewInterface
{
	private Environment $twig;

	public function __construct()
	{
		$loader = new FilesystemLoader(self::TEMPLATE_PATH);
		$this->twig = new Environment($loader);
	}


	public function render(string $tpl, $data = []): string
	{
		return $this->twig->render($tpl, $data);
	}
}
