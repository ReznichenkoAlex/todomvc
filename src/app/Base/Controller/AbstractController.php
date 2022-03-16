<?php
namespace App\Base\Controller;

use App\Base\Container\ContainerInterface;
use App\Base\View\ViewTwig;

abstract class AbstractController
{
	protected ContainerInterface $container;

	public function redirect($url)
    {
        header('Location: ' . $url);
    }

	public function setUser(User $user): void
	{
		$this->user = $user;
	}

	public function render($tpl): string
	{
		$twig = $this->container->get('twig');
		return $twig->render($tpl);
	}

	public function setContainer(ContainerInterface $container): void
	{
		$this->container = $container;
		$this->setServices();
	}

	private function setServices()
	{
		$services = $this->getSubscribedInterfaces();
		foreach ($services as $id => $service) {
			$this->container->set($id, $service);
		}
	}

	private function getSubscribedInterfaces(): array
	{
		return [
			'twig' => ViewTwig::class
		];
	}
}
