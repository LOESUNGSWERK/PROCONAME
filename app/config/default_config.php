<?php
error_reporting(E_ALL & ~E_NOTICE );
ini_set("display_errors", 1);

$loader = include __DIR__.'/../../vendor/autoload.php';

define('LW_MAGIC_QUERY_GET_VAR','zuwujhd');

define('LW_DB_SERVER',		'localhost');
define('LW_DB_PORT',		'3306');
define('LW_DB_NAME',		'');
define('LW_DB_LOGIN',		'');
define('LW_DB_PW',			'');

include __DIR__.'/../core/inc/firstRun.php';
