<?php
// Global variable – path to base folder
const ROOT_PATH = __DIR__;

if (isset($argv) && $command = $argv[1]) {
    runCommand($command);
}

function runCommand($command)
{
    $file = ROOT_PATH.'/commands/'.$command.'.php';

    if (is_file($file)) {
        require_once $file;
    } else {
        echo 'No command found.'.PHP_EOL;
        echo 'Available commands:'.PHP_EOL;
        echo 'test, migrate, seed'.PHP_EOL;
    }
}
