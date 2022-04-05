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

	private array $json_map = [
		'uuid' => 'getUuid',
		'title' => 'getTitle',
		'isCompleted' => 'getIsCompleted',
	];

	public function get()
	{
		if (!$this->isUserSet()) {
			$this->emitUnauthorized();
		}

		$tasks = $this->getActiveTasks($this->user);

		if ($tasks) {
			return $this->jsonResponse($tasks);
		} else {
			return $this->jsonResponse($tasks, 'no tasks');
		}
	}

	public function post()
	{
		if (!$this->isUserSet()) {
			$this->emitUnauthorized();
		}

		$data  = json_decode(file_get_contents(self::JSON_BODY));
		$error = $this->validJson($data);
		if (!$error) {
			$em = $this->getDoctrine();
			try {
				$user = $em->getRepository(User::class)->find($_SESSION['id']);
				$this->createTask($user, $data, $em);
			} catch (Exception $e) {
				return $this->jsonResponse([], $e->getMessage(), 500);
			}

			return $this->jsonResponse($this->getActiveTasks($user));
		} else {
			return $this->jsonResponse([], 'uuid or title is empty', 500);
		}
	}

	public function patch()
	{
		if (!$this->isUserSet()) {
			$this->emitUnauthorized();
		}

		$data  = json_decode(file_get_contents(self::JSON_BODY));
		$error = $this->validJson($data);
		if (!$error) {
			try {
				$this->updateTask($data);
			} catch (Exception $e) {
				return $this->jsonResponse([], $e->getMessage(), 500);
			}

			return $this->jsonResponse($this->getActiveTasks($this->user));
		} else {
			return $this->jsonResponse([], 'uuid or title is empty', 500);
		}
	}

	public function delete()
	{
		if (!$this->isUserSet()) {
			$this->emitUnauthorized();
		}

		$data  = json_decode(file_get_contents(self::JSON_BODY));
		if (!empty($data->uuid)) {
			try {
				$this->deleteTask($data->uuid);
			} catch (Exception $e) {
				return $this->jsonResponse([], $e->getMessage(), 500);
			}

			return $this->jsonResponse($this->getActiveTasks($this->user));
		} else {
			return $this->jsonResponse([], 'uuid is empty', 500);
		}
	}

	private function getJsonArray(array $tasks): array
	{
		$jsonArray = [];

		foreach ($tasks as $task) {
			$obj = [];
			foreach ($this->json_map as $field => $method) {
				$obj[$field] = $task->$method();
			}
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
			return [];
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
		$em   = $this->getDoctrine();
		$repo = $em->getRepository(Task::class);
		/** @var Task $task */
		$task = $repo->findOneBy(['uuid' => $uuid]);

		$task->setIsDeleted(true);
		$em->persist($task);
		$em->flush();
	}

	private function validJson($data)
	{
		$error = false;
		if (empty($data->uuid) || empty($data->title)) {
			$error = true;
		}

		if (isset($data->uuid) && !is_string($data->uuid)) {
			$error = true;
		}

		if (isset($data->title) && !is_string($data->title)) {
			$error = true;
		}
		return $error;
	}
}
