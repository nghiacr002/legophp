<?php

/**
 * Run unit-test with command
 * phpunit --configuration="Application/Setting/phpunit.xml" -v 
 * 
 */
define('SIMPLE_APP', true);
define('APP_DS', DIRECTORY_SEPARATOR);
define('APP_ROOT_PATH', dirname(__FILE__) . APP_DS);
define('APP_PATH', APP_ROOT_PATH . "Application" . APP_DS);
define('APP_PATH_SETTING', APP_PATH . 'Setting' . APP_DS);
define('APP_PATH_LIB', APP_ROOT_PATH . 'Library' . APP_DS);
require_once APP_PATH_SETTING . 'Loader.php';
$_CONF['unit_test'] = true;
$mainApp = new APP\Engine\Application($_CONF);
