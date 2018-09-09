<?php

$configPath = ROOT_PATH.'/config/main.php';

if (is_file($configPath)) {
    $GLOBALS['config'] = require_once $configPath;
} else {
    die('Can\'t start, please copy config/main.php.dist to config/main.php and install your variables.');
}

require_once ROOT_PATH.'/common/db.php';
