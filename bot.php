<?php

$config     = parse_ini_file('config.ini');
$token      = $config['token'];
$website    = "https://api.telegram.org/bot".$token;

// Receber o POST do webhook
$updates    = file_get_contents("php://input");
$updates    = json_decode($updates, TRUE);

// Identificar a mensagem e o id do chat
$text       = $updates['message']['text'];
$chatId     = $updates['message']['chat']['id'];

// Reconhecer os elementos chaves do lembrete
$data       = recognizer($text);
$response   = "";

// Verificar resposta e armazenar
if ($data) {
    extract($data);
    $response = "Lembrete adicionado: \n'$reminder', \ndia $date,\n às $hour.";
    saveReminder($data);
} else {
    $response = "Não conseguimos identificar o que você disse.";
}

// Enviar a mensagem
sendMessage($chatId, $response);

function sendMessage($chatId, $text) {
    $message = urlencode($text);
    $url = $GLOBALS[website]."/sendMessage?chat_id=$chatId&text=$message";
    file_get_contents($url);
}

function recognizer($text) {

    $return = false;

    $regex_date     = '/\d{2}\/\d{2}\/\d{4}/m';
    $regex_tomorrow = '/amanhã|amanha/m';
    $regex_hour     = '/\d{2}:\d{2}/m';
    $regex_msg      = '/^[^,]*/m';

    $has_date       = preg_match($regex_date, $text, $date);
    $has_tomorrow   = preg_match($regex_tomorrow, $text, $tomorrow);
    $has_hour       = preg_match($regex_hour, $text, $hour);
    $has_reminder   = preg_match($regex_msg, $text, $reminder);

    if ($has_tomorrow) {
        $date = what_day_is_tomorrow();
        $has_date = true;
    }

    if ($has_date && $has_hour && $has_reminder) {
        $return = [
            'date'      => $date[0],
            'hour'      => $hour[0],
            'reminder'  => $reminder[0],
        ];
    }
    
    return $return;
}

function what_day_is_tomorrow() {

    $date = new DateTime();
    $date = $date->modify("+1 day");
    $return[0] = $date->format('d/m/Y');
    return $return;
}

function saveReminder($data) {

}