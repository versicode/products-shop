<?php

global $config;

//Initialize database connection, global $db;
$dsn = "mysql:host={$config['db']['host']};dbname={$config['db']['dbname']};charset=utf8;port={$config['db']['port']}";

$GLOBALS['db'] = new PDO($dsn, $config['db']['user'], $config['db']['password']);

// errors on
global $db;
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
