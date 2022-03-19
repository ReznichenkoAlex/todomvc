<?php

namespace App\Controller;

use App\Base\Controller\AbstractController;
use App\Model\Task;
use App\Model\User;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use ReflectionClass;

class ApiController extends AbstractController
{
	const JSON_BODY = 'php://input';

	public function get()
	{
		if (!$this->isUserSet()) {
			$this->emitUnauthorized();
		}

		$tasks = $this->getActiveTasks($this->user);

		return $this->jsonResponse($tasks);
	}

	public function post()
	{
		if (!$this->isUserSet()) {
			$this->emitUnauthorized();
		}

		$data = json_decode(file_get_contents(self::JSON_BODY));
		$em   = $this->getDoctrine();
		try {
			$user = $em->getRepository(User::class)->find($_SESSION['id']);
		} catch (Exception $e) {
			return $this->jsonResponse(['message' => $e->getMessage()]);
		}

		$this->createTask($user, $data, $em);

		$tasks = $this->getActiveTasks($user);

		return $this->jsonResponse($tasks);
	}

	public function patch()
	{
		if (!$this->isUserSet()) {
			$this->emitUnauthorized();
		}

		$data = json_decode(file_get_contents(self::JSON_BODY));

		try {
			$this->updateTask($data);
		} catch (Exception $e) {
			return $this->jsonResponse(['message' => $e->getMessage()]);
		}

		return $this->jsonResponse($this->getActiveTasks($this->user));
	}

	public function delete()
	{
		if (!$this->isUserSet()) {
			$this->emitUnauthorized();
		}

		$data = json_decode(file_get_contents(self::JSON_BODY));

		try {
			$this->deleteTask($data->uuid);
		} catch (Exception $e) {
			return $this->jsonResponse(['message' => $e->getMessage()]);
		}

		return $this->jsonResponse($this->getActiveTasks($this->user));
	}

	private function getJsonArray(array $tasks): array
	{
		$reflection = new ReflectionClass(Task::class);
		$properties = $reflection->getProperties();
		$methods    = $reflection->getMethods();

		$methods = $this->getGetters($methods);
		$keys    = $this->getKeys($properties);
		return $this->MapArray($tasks, $methods, $keys);
	}

	private function getGetters(array $methods): array
	{
		$getters = [];
		foreach ($methods as $method) {
			$methodName = $method->getName();
			if (str_contains($methodName, 'get') && $methodName !== 'getId' && $methodName !== 'getUser' && $methodName !== 'getIsDeleted') {
				$getters[] = $method->getName();
			}
		}
		return $getters;
	}

	private function getKeys(array $properties): array
	{
		$keys = [];
		foreach ($properties as $property) {
			$propertyName = $property->getName();
			if ($propertyName !== 'id' && $propertyName !== 'user' && $propertyName !== 'isDeleted') {
				$keys[] = $property->getName();
			}
		}
		return $keys;
	}

	private function MapArray(array $tasks, array $methods, array $keys): array
	{
		$jsonArray = [];
		foreach ($tasks as $task) {
			$obj = [];
			foreach ($methods as $getter) {
				$obj[] = $task->$getter();
			}
			$obj         = array_combine($keys, $obj);
			$jsonArray[] = $obj;
		}
		return $jsonArray;
	}

	private function getActiveTasks(User $user): array
	{
		$tasks = $user->getTasks()->toArray();

		$tasks = array_filter($tasks, function ($task) {
			/** @var Task $task */
			return !$task->getIsDeleted();
		});

		if ($tasks) {
			return $this->getJsonArray($tasks);
		} else {
			return ['message' => 'no tasks'];
		}
	}

	private function createTask($user, $data, EntityManagerInterface $em): void
	{
		$task = (new Task())
			->setUser($user)
			->setUuid($data->uuid)
			->setTitle($data->title)
			->setIsCompleted($data->isCompleted);
		$em->persist($task);
		$em->flush();
	}

	private function updateTask($data): void
	{
		$em   = $this->getDoctrine();
		$repo = $em->getRepository(Task::class);

		/** @var Task $task */
		$task = $repo->findOneBy(['uuid' => $data->uuid]);


		$task
			->setTitle($data->title)
			->setIsCompleted($data->isCompleted);

		$em->persist($task);
		$em->flush();
	}

	private function deleteTask($uuid)
	{
		$em = $this->getDoctrine();
		$repo = $em->getRepository(Task::class);
		/** @var Task $task */
		$task = $repo->findOneBy(['uuid' => $uuid]);

		$em->remove($task);
		$em->flush();
	}


}
