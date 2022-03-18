<?php
namespace App\Base\Controller;

use App\Base\Container\ContainerInterface;
use App\Base\Utils\DoctrineManager;
use App\Base\View\ViewTwig;
use App\Model\User;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

abstract class AbstractController
{
	protected ContainerInterface $container;
	/**
	 * @var mixed|object
	 */
	protected $user;

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

	protected function redirect($url)
    {
        header('Location: ' . $url);
    }

	protected function getDoctrine()
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

	protected function isUserSet() : bool
	{
		$id = $_SESSION['id'] ?? null;
		if($id) {
			/** @var EntityManagerInterface $em */
			$em = $this->getDoctrine();
			$user = $em->getRepository(User::class)->find($id);
			if ($user) {
				$this->user = $user;
				return true;
			}
		}
		return false;
	}

	protected function jsonResponse($data)
	{
		Header('Content-Type: application/json; charset=utf-8');
		return json_encode($data);
	}

	protected function emitUnauthorized()
	{
		header('HTTP/1.1 401 Unauthorized');
		http_response_code('401');
		throw new Exception('Unauthorized');
	}
}
