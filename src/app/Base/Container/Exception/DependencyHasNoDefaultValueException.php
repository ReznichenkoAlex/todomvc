<?php

namespace App\Base\Container\Exception;

use App\Base\Container\NotFoundExceptionInterface;

class DependencyHasNoDefaultValueException extends ContainerException implements NotFoundExceptionInterface
{

}
