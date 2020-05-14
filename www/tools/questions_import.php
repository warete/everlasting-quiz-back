<?php
$_SERVER['DOCUMENT_ROOT'] = dirname(realpath(__DIR__ . '/'));
require_once $_SERVER['DOCUMENT_ROOT'] . '/bootstrap.php';

\App\QuestionsImport::run();
