<?php
require_once __DIR__ . '/vendor/autoload.php';
Dotenv\Dotenv::createImmutable(__DIR__)->load();

define('REDBEAN_MODEL_PREFIX', '\\Models\\');
define('REDBEAN_MODEL_PREFIX', '');

define('QUESTIONS_TABLE', 'questions');
define('USERS_TABLE', 'users');
define('CATEGORIES_TABLE', 'categories');
define('SCORES_TABLE', 'scores');

\RedBeanPHP\R::setup('mysql:host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_DATABASENAME'], $_ENV['DB_USER'], $_ENV['DB_PASS']);
