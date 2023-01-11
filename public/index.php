<?php

declare(strict_types = 1);

$root = dirname(__DIR__) . DIRECTORY_SEPARATOR;

define('APP_PATH', $root . 'app' . DIRECTORY_SEPARATOR);
define('FILE_DATA', $root . 'transaction_files' . DIRECTORY_SEPARATOR.'sample_1.csv');
define('VIEWS_PATH', $root . 'views' . DIRECTORY_SEPARATOR .'transactions.php');

/* YOUR CODE (Instructions in README.md) */

require_once APP_PATH . 'app.php';

$appData = app(FILE_DATA);
require_once VIEWS_PATH;









