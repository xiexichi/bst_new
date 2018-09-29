<?php
if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');
define('APP_DEBUG',true);
define('DOC_ROOT', dirname(__FILE__));
define('APP_PATH','./app/');
require './lib/run.php';
