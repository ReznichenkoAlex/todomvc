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
	 * @ORM\GeneratedValue
	 * @ORM\Column(type="integer")
	 */
	private $id;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $uuid;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $title;

	/**
	 * @ORM\Column(type="boolean")
	 */
	private $isCompleted;

	/**
	 * @ORM\Column(type="boolean")
	 */
	private $isDeleted;

	/**
	 * @ORM\ManyToOne(targetEntity=User::class, inversedBy="tasks")
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $user;

	public function getId(): ?int
	{
		return $this->id;
	}

	public function getUuid(): ?string
	{
		return $this->uuid;
	}

	public function setUuid(string $uuid): self
	{
		$this->uuid = $uuid;

		return $this;
	}

	public function getTitle(): ?string
	{
		return $this->title;
	}

	public function setTitle(string $title): self
	{
		$this->title = $title;

		return $this;
	}

	public function getIsCompleted(): ?bool
	{
		return $this->isCompleted;
	}

	public function setIsCompleted(bool $isCompleted): self
	{
		$this->isCompleted = $isCompleted;

		return $this;
	}

	public function getIsDeleted(): ?bool
	{
		return $this->isDeleted;
	}

	public function setIsDeleted(bool $isDeleted): self
	{
		$this->isDeleted = $isDeleted;

		return $this;
	}

	public function getUser(): ?User
	{
		return $this->user;
	}

	public function setUser(?User $user): self
	{
		$this->user = $user;

		return $this;
	}
}
