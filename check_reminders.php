<?php

require_once("connect.php");
require_once("functions.php");

$config     = parse_ini_file('config.ini');
$token      = $config['token'];
$website    = "https://api.telegram.org/bot".$token;

$dateTime = new DateTime();
$initialTime    = $dateTime->format('h:i');
$limitTime      = $dateTime->modify('+10 minutes');
$limitTime      = $dateTime->format('h:i');

$msg            = "Vai pegar das $initialTime ate as $limitTime";

sendMessage("747786172", $msg);
