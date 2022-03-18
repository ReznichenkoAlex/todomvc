<?php

namespace App;

use App\Base\Container\Container;
use App\Base\Container\ContainerInterface;
use App\Base\Controller\AbstractController;
use App\Base\Router\Router;
use App\Base\Utils\Exception\AppException;
use App\Base\Utils\RouterReader\TestRouterReader;
use Exception;

class Application
{
	private Router             $router;
	private AbstractController $controller;
	private string             $actionName;
	private ContainerInterface $container;

	public function __construct()
	{
		$this->router    = new Router(new TestRouterReader());
		$this->container = new Container();
	}

	public function run()
	{
		session_start();
		try {
			$this->router->run();
			$this->initController();
			$this->initAction();

			$content = $this->controller->{$this->actionName}();

			echo $content;
		} catch (Exception $e) {
			echo $e->getMessage();
		}

	}

	private function initController()
	{
		$controllerName = $this->router->getControllerName();
		if (!class_exists($controllerName)) {
			$this->emitServiceUnavailable();
			throw new AppException('Controller ' . $controllerName . ' not found');
		}

		$this->controller = new $controllerName();
		$this->controller->setContainer($this->container);
	}

	private function initAction()
	{
		$actionName = $this->router->getActionName();
		if (!method_exists($this->controller, $actionName)) {
			$this->emitServiceUnavailable();
			throw new AppException('Action ' . $actionName . ' not found in ' . get_class($this->controller));
		}

		$this->actionName = $actionName;
	}

	private function emitServiceUnavailable(): void
	{
		http_response_code('503');
		header("Status: 503 Service Unavailable");
	}
}
