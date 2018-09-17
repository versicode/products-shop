<?php

global $config;

$memcached = new Memcached();
$memcached->addServer($config['memcached']['host'], $config['memcached']['port']);

$GLOBALS['memcached'] = $memcached;
