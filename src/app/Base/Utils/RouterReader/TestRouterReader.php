<?php

namespace App\Base\Utils\RouterReader;

class TestRouterReader implements RouterReaderInterface
{
    public function parseRoutes(string $path): array
    {
        return [
            '/'     => [
                'controller' => 'App\Controller\MainController',
                'action'     => 'index'
            ],
            '/admin' => [
                'controller' => 'App\Controller\AdminController',
                'action'     => 'index'
            ]
        ];
    }
}