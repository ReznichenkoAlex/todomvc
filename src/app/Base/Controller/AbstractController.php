<?php

namespace App\Base\Controller;

use App\Base\Container\ContainerInterface;
use App\Base\Utils\DoctrineManager;
use App\Base\Utils\Exception\UnauthorizedException;
use App\Base\View\View;
use App\Model\User;
use Doctrine\ORM\EntityManagerInterface;

abstract class AbstractController
{
	protected ContainerInterface $container;

	protected User $user;

	private function getSubscribedServices(): array
	{
		return [
			'twig'     => View::class,
			'doctrine' => DoctrineManager::class,
		];
	}

	public function setContainer(ContainerInterface $container): void
	{
		$this->container = $container;
		$this->setServices();
	}

	protected function redirect($url)
	{
		header('Location: ' . $url);
	}

	protected function getDoctrine(): EntityManagerInterface
	{
		$doctrineManager = $this->container->get('doctrine');
		return $doctrineManager->getEntityManager();
	}

	protected function render($tpl, $parameters = []): string
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

	protected function isUserSet(): bool
	{
		$id = $_SESSION['id'] ?? null;
		if ($id) {
			$em         = $this->getDoctrine();
			$userObject = $em->getRepository(User::class)->find($id);
			if ($userObject) {
				$this->user = $userObject;
				return true;
			}
		}
		return false;
	}

	protected function jsonResponse($data, $message = '', $status = 200)
	{
		Header('Content-Type: application/json; charset=utf-8');
		http_response_code($status);
		return json_encode([
							   'message' => $message,
							   'data'    => $data
						   ]);
	}

	protected function emitUnauthorized()
	{
		header('HTTP/1.1 401 Unauthorized');
		http_response_code('401');
		throw new UnauthorizedException('Unauthorized');
	}
}
