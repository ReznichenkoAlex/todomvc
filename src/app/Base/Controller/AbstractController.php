<?php
namespace App\Base\Controller;

use App\Base\Container\ContainerInterface;
use App\Base\Utils\DoctrineManager;
use App\Base\View\ViewTwig;

abstract class AbstractController
{
	protected ContainerInterface $container;

	private function getSubscribedServices(): array
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
		$doctrineManager = $this->container->get('doctrine');
		return $doctrineManager->getEntityManager();
	}

	public function render($tpl, $parameters = []): string
	{
		$twig = $this->container->get('twig');
		return $twig->render($tpl, $parameters);
	}

	private function setServices()
	{
		$services = $this->getSubscribedServices();
		foreach ($services as $id => $service) {
			$this->container->set($id, $service);
		}
	}
}
