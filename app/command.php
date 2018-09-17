<?php

// Global variable – path to base folder
const ROOT_PATH = __DIR__;

$configPath = ROOT_PATH.'/config/main.php';

if (is_file($configPath)) {
    $GLOBALS['config'] = require_once $configPath;
} else {
    die('Can\'t start, please copy config/main.php.dist to config/main.php and install your variables.'.PHP_EOL);
}

require_once ROOT_PATH.'/common/db.php';
require_once ROOT_PATH.'/common/memcached.php';
require_once ROOT_PATH.'/common/cacher.php';
require_once ROOT_PATH.'/models/product.php';

if (isset($argv) && $command = $argv[1]) {
    runCommand($command);
}

function runCommand($command)
{
    global $argv;

    $file = ROOT_PATH.'/commands/'.$command.'.php';

    if (is_file($file)) {
        require_once $file;

        run();
    } else {
        echo 'No command found.'.PHP_EOL;
        echo 'Available commands:'.PHP_EOL;
        echo 'test, migrate, seed'.PHP_EOL;
    }
}
