<?php
Dotenv\Dotenv::createImmutable(__DIR__)->load();

return [
	'paths' => [
		'migrations' => __DIR__ . '/db/migrations',
	],
	'environments' => [
		'default_database' => 'production',
		'default_migration_table' => 'phinxlog',
		'production' =>	[
			'adapter' => 'mysql',
			'host' => $_ENV['DB_HOST'],
			'name' => $_ENV['DB_DATABASENAME'],
			'user' => $_ENV['DB_USER'],
			'pass' => $_ENV['DB_PASS'],
			'port' => 3306,
			'charset' => 'utf8',
			'collation' => 'utf8_unicode_ci',
		],
	],
];
