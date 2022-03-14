<?php

namespace App\Controller;

use App\Base\Controller\AbstractController;

class MainController extends AbstractController
{
    public function index()
    {
        return include_once PROJECT_ROOT_DIR . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'View' . DIRECTORY_SEPARATOR . 'index.html';
    }
}
