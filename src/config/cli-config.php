<?php
// cli-config.php
use Doctrine\ORM\Tools\Console\ConsoleRunner;

// replace with file to your own project bootstrap
require_once __DIR__ . '/bootstrap.php';

/** @var Doctrine\ORM\EntityManager $entityManager */
return ConsoleRunner::createHelperSet($entityManager);
