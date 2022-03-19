<?php

namespace App\Base\Container;

use App\Base\Container\Exception\DependencyHasNoDefaultValueException;
use ReflectionClass;
use App\Base\Container\Exception\DependencyIsNotInstantiableException;
use ReflectionParameter;

class Container implements ContainerInterface
{
	private array $services = [];

	/**
	 * @inheritDoc
	 */
	public function get($id)
	{
		if (!$this->has($id)) {
			$this->set($id);
		}
		$service = $this->services[$id];
		return $this->createService($service);
	}

	/**
	 * @inheritDoc
	 */
	public function has($id): bool
	{
		return isset($this->services[$id]);
	}

	public function set(string $id, $service = null)
	{
		if ($service === null) {
			$service = $id;
		}
		$this->services[$id] = $service;
	}

	private function createService($service)
	{
		$reflection = new ReflectionClass($service);

		if (!$reflection->isInstantiable()) {
			throw new DependencyIsNotInstantiableException($service . ' service is not instantiable');
		}

		$constructor = $reflection->getConstructor();

		if (is_null($constructor)) {
			$reflection->newInstance();
		}

		$parameters   = $constructor->getParameters();
		$dependencies = $this->getDependencies($parameters);

		return $reflection->newInstanceArgs($dependencies);
	}

	private function getDependencies(array $parameters)
	{
		$dependencies = [];

		/** @var ReflectionParameter $parameter */
		foreach ($parameters as $parameter) {
			$dependency = get_class($parameter);
			if (is_null($dependency)) {
				if ($parameter->isDefaultValueAvailable()) {
					$dependencies[] = $parameter->getDefaultValue();
				} else {
					throw new DependencyHasNoDefaultValueException('can\'t resolve class dependency ' . $parameter->name);
				}
			} else {
				$dependencies[] = $this->get($parameter->getName());
			}
		}

		return $dependencies;
	}
}
