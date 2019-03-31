<?php
spl_autoload_register(function ($className) {
    $path = false;

    $elements = array_filter(explode('\\', $className), function ($v) { return ! empty($v);});

    $path = realpath (__DIR__ . '/' . implode('/', $elements) . '.php');

    if ($path !== false) {require_once $path;}
});