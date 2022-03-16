<?php

namespace App\Model;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * @ORM\Entity
 * @ORM\Table(name="task")
 */

class Task
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue
	 */
	private $id;

	/**
	 * Many tasks have one user. This is the owning side.
	 * @ManyToOne(targetEntity="User", inversedBy="tasks")
	 * @JoinColumn(name="task_id", referencedColumnName="id")
	 */
	private $user;
	/**
	 * @ORM\Column(type="string")
	 */
	private $text;
	/**
	 * @ORM\Column(type="boolean")
	 */
	private $isComplete;

	public function getId():int
	{
		return $this->id;
	}

	public function setId($id): self
	{
		$this->id = $id;

		return $this;
	}

	public function getUser(): User
	{
		return $this->user;
	}

	public function setUser($user): self
	{
		$this->user = $user;

		return $this;
	}

	public function getText()
	{
		return $this->text;
	}

	public function setText($text): self
	{
		$this->text = $text;

		return $this;
	}

	public function getIsComplete()
	{
		return $this->isComplete;
	}

	public function setIsComplete($isComplete): self
	{
		$this->isComplete = $isComplete;

		return $this;
	}
}
