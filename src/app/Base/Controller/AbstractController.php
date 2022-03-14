<?php
namespace App\Base\Controller;

use App\Base\View\ViewInterface;
use App\Model\User;

abstract class AbstractController
{
	protected ViewInterface $view;
	protected ?User $user = null;

	public function redirect($url)
    {
        header('Location: ' . $url);
    }

	public function setView(ViewInterface $view): void
	{
		$this->view = $view;
	}

	public function setUser(User $user): void
	{
		$this->user = $user;
	}

	public function render($tpl): string
	{
		return $this->view->render($tpl);
	}
}
