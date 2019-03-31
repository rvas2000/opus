<?php

use Opus\App;

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (! defined('BASE_PATH')) define('BASE_PATH', realpath(__DIR__ . '/..'));
if (! defined('CONFIG_PATH')) define('CONFIG_PATH', realpath(__DIR__ . '/../Site/config'));

require_once BASE_PATH . '/Opus/autoload.php';
require_once BASE_PATH . '/Site/autoload.php';

$app = App::getInstance();

$app->run();




