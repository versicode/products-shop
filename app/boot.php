<?php

$configPath = ROOT_PATH.'/config/main.php';

if (is_file($configPath)) {
    $GLOBALS['config'] = require_once $configPath;
} else {
    die('Can\'t start, please copy config/main.php.dist to config/main.php and install your variables.');
}

require_once ROOT_PATH.'/common/db.php';
require_once ROOT_PATH.'/common/router.php';
require_once ROOT_PATH.'/common/render.php';
require_once ROOT_PATH.'/helpers/input.php';

require_once ROOT_PATH.'/models/product.php';

global $config;

$uri = $_SERVER['REQUEST_URI'] !== '/' ? $_SERVER['REQUEST_URI'] : $config['router']['default'];

router\resolve(router\normalizeUri($uri), $config['router']['404'])($_GET, $_POST);
