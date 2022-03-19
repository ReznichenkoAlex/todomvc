<?php

namespace App\Base\Utils;

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;


class DoctrineManager
{
	private array $dbParams = [
		'driver'   => DB_DRIVER,
		'user'     => DB_USER,
		'password' => DB_PASSWORD,
		'dbname'   => DB_NAME,
		'host'     => DB_HOST
	];

	private array         $paths = [
		__DIR__ . "/../../Model/"
	];
	private EntityManager $entityManager;

	public function __construct()
	{
		$config              = Setup::createAnnotationMetadataConfiguration(
			$this->paths,
			false,
			null,
			null,
			false);
		$this->entityManager = EntityManager::create($this->dbParams, $config);
	}

	public function getEntityManager(): EntityManager
	{
		return $this->entityManager;
	}
}
