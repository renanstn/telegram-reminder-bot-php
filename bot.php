<?php

$config = parse_ini_file('config.ini');
$token = $config['token'];
$website = "https://api.telegram.org/bot".$token;

// Receber o POST do webhook
$updates = file_get_contents("php://input");
$updates = json_decode($updates, TRUE);

// Identificar a mensagem e o id do chat
$text = $updates['message']['text'];
$chatId = $updates['message']['chat']['id'];

sendMessage($chatId, $text);

function sendMessage($chatId, $text) {
    $message = urlencode($text);
    $url = $GLOBALS[website]."/sendMessage?chat_id=$chatId&text=$message";
    file_get_contents($url);
}
