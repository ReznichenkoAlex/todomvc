<?php
namespace App\Base\Controller;

abstract class AbstractController
{
	public function redirect($url)
    {
        header('Location: ' . $url);
    }
}
