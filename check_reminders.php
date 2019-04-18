<?php

date_default_timezone_set('America/Sao_Paulo');

require_once("connect.php");
require_once("functions.php");

$config     = parse_ini_file('config.ini');
$token      = $config['token'];
$website    = "https://api.telegram.org/bot$token";

$lastRun        = new DateTime(getLastRunTime($conn));
$hourOfLastRun  = $lastRun->format('H:i');
$dateTime       = new DateTime();
$date           = $dateTime->format('Y-m-d');
//$initialTime    = $dateTime->format('H:i');
$finalTime      = $dateTime->modify('+9 minutes');
$finalTime      = $dateTime->format('H:i');
/* Considera os lembretes adicionados da última hora
que o cript foi executado, até os 9 minutos futuros
do horário atual.
Pega da última hora pois as vezes o webhost vacila e não
roda o script.
Pega até os 9 minutos futuros pois o webhost roda o CRON
a cada 10 minutos (ou pelo menos deveria). */

$reminders = checkReminders($date, $hourOfLastRun, $finalTime, $conn);

saveRun($conn);
saveLog($reminders, $hourOfLastRun, $finalTime);

if (count($reminders)) {
    foreach ($reminders as $reminder) {
        $chatId = $reminder['chat_id'];
        $content = $reminder['content'];
        sendMessage($chatId, $content);
    }
}

