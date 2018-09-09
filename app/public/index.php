<?php

// Global variable – path to base folder
const ROOT_PATH = __DIR__.'/..';

// Import all code used by app
require_once ROOT_PATH.'/boot.php';

use faker;

echo faker\generateRandomString();
