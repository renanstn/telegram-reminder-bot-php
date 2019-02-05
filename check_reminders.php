<?php

date_default_timezone_set('America/Sao_Paulo');

require_once("connect.php");
require_once("functions.php");

$config     = parse_ini_file('config.ini');
$token      = $config['token'];
$website    = "https://api.telegram.org/bot$token";

$dateTime       = new DateTime();
$date           = $dateTime->format('Y-m-d');
$initialTime    = $dateTime->format('H:i');
$finalTime      = $dateTime->modify('+9 minutes');
$finalTime      = $dateTime->format('H:i');

$reminders      = checkReminders($date, $initialTime, $finalTime, $conn);

if (count($reminders)) {
    foreach ($reminders as $reminder) {
        $chatId = $reminder['chat_id'];
        $content = $reminder['content'];
        sendMessage($chatId, $content);
    }
}

