<?php
namespace App\Base\Controller;

use App\Base\Container\ContainerInterface;
use App\Base\Utils\DoctrineManager;
use App\Base\View\ViewTwig;

abstract class AbstractController
{
	protected ContainerInterface $container;

	private function getSubscribedInterfaces(): array
	{
		return [
			'twig' => ViewTwig::class,
			'doctrine' => DoctrineManager::class,
		];
	}

	public function setContainer(ContainerInterface $container): void
	{
		$this->container = $container;
		$this->setServices();
	}

	public function redirect($url)
    {
        header('Location: ' . $url);
    }

	public function getDoctrine()
	{
		return $this->container->get('doctrine');
	}

	public function render($tpl): string
	{
		$twig = $this->container->get('twig');
		return $twig->render($tpl);
	}

	private function setServices()
	{
		$services = $this->getSubscribedInterfaces();
		foreach ($services as $id => $service) {
			$this->container->set($id, $service);
		}
	}
}
