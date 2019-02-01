<?php

require "connect.php";
require "functions.php";

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
    if (hourIsValid($hour)) {
        $response = "Lembrete adicionado: \n'$reminder', \ndia $date,\n às $hour.";
        saveReminder($chatId, $data, $conn);
    } else {
        $response = "Por favor, utilize minutos arredondados para seus lembretes.";
    }
} else {
    $response = "Não conseguimos identificar o que você disse.";
}

// Enviar a mensagem
sendMessage($chatId, $response);
