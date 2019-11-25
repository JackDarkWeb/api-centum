<?php
define('WEBROOT', dirname(__FILE__));
define('ROOT', dirname(WEBROOT));
define('DS', DIRECTORY_SEPARATOR);
define('ASSETS',WEBROOT.DS);
define('CORE',ROOT.DS.'core');
define('BASE_URL', dirname(dirname($_SERVER['SCRIPT_NAME'])));

define('db_message', ROOT. DS . 'data_messages' . DS . 'messages.json');

define('PUBLIC_FOLDER', ROOT.DS.'public');

require_once CORE.DS.'includes.php';


new Dispatcher();

