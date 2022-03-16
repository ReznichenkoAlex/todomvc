<?php

namespace App\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 */
class User
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue
	 */
	private $id;
	/**
	 * @ORM\Column(type="string")
	 */
	private $email;
	/**
	 * @ORM\Column(type="string")
	 */
	private $password;

	/**
	 * One user has many tasks. This is the inverse side.
	 * @OneToMany(targetEntity="Task", mappedBy="user")
	 */
	private $tasks;

	public function __construct()
	{
		$this->tasks = new ArrayCollection();
	}

	public function getId(): int
	{
		return $this->id;
	}

	public function setId($id): self
	{
		$this->id = $id;

		return $this;
	}

	public function getEmail(): string
	{
		return $this->email;
	}

	public function setEmail($email): self
	{
		$this->email = $email;

		return $this;
	}

	public function getPassword(): string
	{
		return $this->password;
	}

	public function setPassword($password): self
	{
		$this->password = $password;

		return $this;
	}

	public function getTasks(): ArrayCollection
	{
		return $this->tasks;
	}

	public function setTasks(ArrayCollection $tasks): self
	{
		$this->tasks = $tasks;

		return $this;
	}
}
