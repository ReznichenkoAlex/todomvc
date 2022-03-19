<?php

namespace App\Base\Router;

use App\Base\Utils\Exception\RouterException;
use App\Base\Utils\RouterReader\RouterReaderInterface;

class Router
{
	private string                $controllerName;
	private string                $actionName;
	private array                 $routes    = [];
	private bool                  $processed = false;
	private RouterReaderInterface $routerReader;

	public function __construct(RouterReaderInterface $routerReader)
	{
		$this->routerReader = $routerReader;
		$this->fillRoutes();
	}

	private function fillRoutes()
	{
		$this->routes = $this->routerReader->parseRoutes();
	}

	public function run()
	{
		if (!$this->processed) {
			$path = parse_url($_SERVER['REQUEST_URI'])['path'];

			if (isset($this->routes[$path])) {
				if (isset($this->routes[$path]['method']) && $this->routes[$path]['method'] !== $_SERVER['REQUEST_METHOD']) {
					throw new RouterException('method ' . $_SERVER['REQUEST_METHOD'] . ' is not allowed for ' . $path);
				}
				$this->controllerName = $this->routes[$path]['controller'];
				$this->actionName     = $this->routes[$path]['action'];
			} else {
				$this->emitPageNotFound();
			}

			$this->processed = true;
		}
	}


	public function getControllerName(): string
	{
		return $this->controllerName;
	}

	public function getActionName(): string
	{
		return $this->actionName;
	}

	private function emitPageNotFound()
	{
		$host = 'http://' . $_SERVER['HTTP_HOST'] . '/';
		http_response_code('404');
		header("Status: 404 Not Found");
		header('Location:' . $host . '404');
		throw new RouterException('Route not found');
	}

}
