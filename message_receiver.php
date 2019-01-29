<?php

require_once('vendor/autoload.php');
$config = parse_ini_file("./config.ini");
$botId  = $config['botId'];

$bot = new \TelegramBot\Api\BotApi($botId);
$updates = $bot->getUpdates();

echo "<pre>";
print_r($updates[0]->getMessage()->getText());
