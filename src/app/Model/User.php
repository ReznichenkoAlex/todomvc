<?php

namespace App\Model;

use App\Base\Model\AbstractModel;
use App\Base\Utils\Db;

class User extends AbstractModel
{

	private $id;
	private $name;
	private $password;
	private $createdAt;

	public function __construct($data = [])
	{
		if ($data) {
			$this->id = $data['id'];
			$this->name = $data['name'];
			$this->password = $data['password'];
			$this->createdAt = $data['created_at'];
		}
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function setName(string $name)
	{
		$this->name = $name;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @param mixed $id
	 */
	public function setId(int $id): self
	{
		$this->id = $id;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getPassword()
	{
		return $this->password;
	}

	/**
	 * @param mixed $password
	 */
	public function setPassword($password): self
	{
		$this->password = $password;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getCreatedAt(): string
	{
		return $this->createdAt;
	}

	/**
	 * @param mixed $createdAt
	 */
	public function setCreatedAt(string $createdAt): self
	{
		$this->createdAt = $createdAt;
		return $this;
	}

	/**
	 * @param mixed $gender
	 */
	public function setGender(int $gender): self
	{
		$this->gender = $gender;
		return $this;
	}

	public function save()
	{
		$db = Db::getInstance();
		$insert = "INSERT INTO User (`name`, `password`) VALUES (
            :name, :password
        )";
		$db->exec($insert, __METHOD__, [
			':name' => $this->name,
			':password' => $this->password,
		]);

		$this->id = $db->lastInsertId();

		return $db->lastInsertId();
	}

	public static function getById(int $id): ?self
	{
		$db = Db::getInstance();
		$select = "SELECT * FROM users WHERE id = $id";
		$data = $db->fetchOne($select, __METHOD__);

		if (!$data) {
			return null;
		}

		return new self($data);
	}

	public static function getByName(string $name): ?self
	{
		$db = Db::getInstance();
		$select = "SELECT * FROM users WHERE `name` = :name";
		$data = $db->fetchOne($select, __METHOD__, [
			':name' => $name
		]);

		if (!$data) {
			return null;
		}

		return new self($data);
	}

	public static function getPasswordHash(string $password)
	{
		return sha1(',.lskfjl' . $password);
	}
}
