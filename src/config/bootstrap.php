<?php
// bootstrap.php
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/config.php';

$paths     = array(__DIR__ . "/../app/Model/");
$isDevMode = false;
$proxyDir = null;
$cache = null;
$useSimpleAnnotationReader = false;

// the connection configuration
$dbParams = array(
	'driver'   => DB_DRIVER,
	'user'     => DB_USER,
	'password' => DB_PASSWORD,
	'dbname'   => DB_NAME,
	'host'     => DB_HOST
);

$config        = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode, $proxyDir, $cache, $useSimpleAnnotationReader);
$entityManager = EntityManager::create($dbParams, $config);
