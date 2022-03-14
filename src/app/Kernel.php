<?php

namespace App;

use App\Base\Controller\AbstractController;
use App\Base\Router\Router;
use App\Base\Utils\RouterReader\TestRouterReader;

class Kernel
{
    private Router             $router;
    private AbstractController $controller;
    private string             $actionName;

    public function __construct()
    {
        $this->router = new Router(new TestRouterReader());
    }

    public function run()
    {
        session_start();
        try {
            $this->router->run();

            $this->initController();
            $this->initAction();

            $this->controller->{$this->actionName}();

        } catch (\Exception $e) {
            echo $e->getMessage();
            die;
        }

    }

    private function initController()
    {
        $controllerName = $this->router->getControllerName();
        if (!class_exists($controllerName)) {
            throw new \Exception('Controller ' . $controllerName . ' not found');
        }

        $this->controller = new $controllerName();
    }

    private function initAction()
    {
        $actionName = $this->router->getActionName();
        if (!method_exists($this->controller, $actionName)) {
            throw new \Exception('Action ' . $actionName . ' not found in ' . get_class($this->controller));
        }

        $this->actionName = $actionName;
    }

}