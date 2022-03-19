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
            '/api/get' => [
                'controller' => 'App\Controller\ApiController',
                'action'     => 'get',
				'method' => 'GET'
            ],
            '/api/post' => [
                'controller' => 'App\Controller\ApiController',
                'action'     => 'post',
				'method' => 'POST'
            ],
            '/api/patch' => [
                'controller' => 'App\Controller\ApiController',
                'action'     => 'patch',
				'method' => 'PATCH'
            ],
            '/api/delete' => [
                'controller' => 'App\Controller\ApiController',
                'action'     => 'delete',
				'method' => 'DELETE'
            ],
        ];
    }
}
