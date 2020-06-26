<?php

ini_set('error_reporting', (string) \E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');

$files = new Illuminate\Filesystem\Filesystem;

if ($files->isDirectory($storage = __DIR__ . '/temp')) {
    $files->deleteDirectory($storage);
}

$files->makeDirectory($storage);
