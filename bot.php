<?php

require_once('vendor/autoload.php');

use TelegramBot\Api\BotApi;

$config = parse_ini_file("./config.ini");

$botId  = $config['botId'];
$chatId = $config['chatId'];

$bot = new BotApi($botId);
$bot->sendMessage($chatId, "Oieeeee");
