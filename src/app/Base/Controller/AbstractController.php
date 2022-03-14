<?php
namespace App\Base\Controller;

use App\Base\View\ViewInterface;

abstract class AbstractController
{
	private ViewInterface $view;

	public function redirect($url)
    {
        header('Location: ' . $url);
    }

	public function setView(ViewInterface $view): void
	{
		$this->view = $view;
	}

	public function render($tpl): string
	{
		return $this->view->render($tpl);
	}
}
