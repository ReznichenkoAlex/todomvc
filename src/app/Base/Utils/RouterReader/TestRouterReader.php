<?php

namespace App\Base\Utils\RouterReader;

class TestRouterReader implements RouterReaderInterface
{
	private string $path;

	public function __construct(string $path = 'test')
	{
		$this->path = $path;
	}

	public function parseRoutes(): array
    {
        return [
            '/'     => [
                'controller' => 'App\Controller\MainController',
                'action'     => 'index'
            ],
            '/user/register' => [
                'controller' => 'App\Controller\AuthController',
                'action' => 'register'
            ],
            '/user/login' => [
                'controller' => 'App\Controller\AuthController',
                'action' => 'login'
            ],
            '/admin' => [
                'controller' => 'App\Controller\AdminController',
                'action'     => 'index'
            ]
        ];
    }
}
