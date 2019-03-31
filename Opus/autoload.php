<?php
if (! defined('BASE_PATH')) define('BASE_PATH', realpath(__DIR__ . '/..'));
if (! defined('CONFIG_PATH')) define('CONFIG_PATH', realpath(__DIR__ . '/../Site/config'));

spl_autoload_register(function ($className) {
    $path = false;

    $elements = array_filter(explode('\\', $className), function ($v) { return ! empty($v);});

    if (count($elements) > 1 && ($element = array_shift($elements)) == 'Opus') {
        $path = realpath (__DIR__ . '/' . implode('/', $elements) . '.php');
    }


    if ($path !== false) {require_once $path;}
});