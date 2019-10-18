<?php

date_default_timezone_set('America/Sao_Paulo');

require_once("connect.php");
require_once("functions.php");

$config     = parse_ini_file('config.ini');
$token      = $config['token'];
$website    = "https://api.telegram.org/bot".$token;

// Receber o POST do webhook
$updates    = file_get_contents("php://input");
$updates    = json_decode($updates, TRUE);

// Identificar a mensagem e o id do chat
$text       = $updates['message']['text'];
$chatId     = $updates['message']['chat']['id'];

// Verificar se não é o comando getLog (uso do admin)
if (strpos($text, '/getlog') !== false) {
    $response = getLog();
    sendMessage($chatId, $response);
    die;
}

// Verificar se o comando não é o /start
if (strpos($text, '/start') !== false) {
    $response = "Seja bem vindo ao ReminderMessagesBot.\n\nPara adicionar um lembrete, digite naturalmente como nos exemplos abaixo:\n'Entregar trabalho hoje as 19:00'\n'Entregar trabalho, amanhã, as 08:00'\n'Entregar trabalho, dia 12/10/2019 07:30'\n\nEu consigo identificar palavras chaves como 'hoje' e 'amanhã' para salvar seus lembretes, ou você pode digitar a data completa.\nTodo lembrete precisa ter um horário, informe sempre o horário no formato 'hh:mm'.\n\nQuando chegar o dia e a hora marcada, eu te mando uma mensagem ;)";
    sendMessage($chatId, $response);
    die;
}

// Reconhecer os elementos chaves do lembrete
$data       = recognizer($text);
$response   = "";

// Verificar resposta e armazenar
if ($data) {
    extract($data);
    $response = "Lembrete adicionado: \n'$reminder', \ndia $date,\n às $hour.";
    saveReminder($chatId, $data, $conn);
} else {
    $response = "Não conseguimos identificar o que você disse.";
}

// Enviar a mensagem
sendMessage($chatId, $response);
