<?php
require_once __DIR__ . '/vendor/autoload.php';
Dotenv\Dotenv::createImmutable(__DIR__)->load();

define('REDBEAN_MODEL_PREFIX', '\\Models\\');
define('REDBEAN_MODEL_PREFIX', '');

\RedBeanPHP\R::setup('mysql:host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_DATABASENAME'], $_ENV['DB_USER'], $_ENV['DB_PASS']);
