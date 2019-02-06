<?php
include_once(realpath(dirname(__FILE__)). "/".'../vendor/autoload.php');
$dotenv = Dotenv\Dotenv::create(realpath(dirname(__FILE__)). "/".'../');
$dotenv->load();

$db_name = getenv('DB_NAME');
$db_host = getenv('DB_HOST');
$db_username = getenv('DB_USERNAME');

$db_name_target = getenv('DB_NAME_TARGET');
$db_host_target  = getenv('DB_HOST_TARGET');
$db_username_target  = getenv('DB_USERNAME_TARGET');

$DB_CON = new PDO("mysql:host={$db_host};dbname=${db_name}", "{$db_username}");
$DB_CON_NEW = new PDO("mysql:host={$db_host_target};dbname=${db_name_target}", "{$db_username_target}");
