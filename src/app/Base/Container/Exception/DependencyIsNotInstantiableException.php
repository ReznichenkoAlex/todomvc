<?php
namespace  App\Base\Container\Exception;

use App\Base\Container\NotFoundExceptionInterface;

class DependencyIsNotInstantiableException extends ContainerException implements NotFoundExceptionInterface
{

}
