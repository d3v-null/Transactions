<?php

/**
 * Library Settings
 */
require_once __DIR__."/idiorm.php";
require_once __DIR__."/User.class.php";
require_once __DIR__."/helpers.php";

/**
 * Database Settings
 */
$db_host = 'localhost';
$db_name = 'user_db';
$db_user = 'root';
$db_pass = '';

ORM::configure("mysql:host=$db_host;dbname=$db_name");
ORM::configure("username", $db_user);
ORM::configure("password", $db_pass);
ORM::configure('driver_options', array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
ORM::configure('return_result_sets', true);

/**
 * Session Settings
 */
session_name('tzreg');
session_start();

/**
 * Email Settings
 */
$fromEmail = "xiaofan2406@me.com";

if(!$fromEmail){
	$fromEmail = 'noreply@'.$_SERVER['SERVER_NAME'];
}
