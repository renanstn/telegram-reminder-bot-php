<?php
require __DIR__ . '/vendor/autoload.php';

$config = parse_ini_file("config.ini");

$bot_api_key  = $config['botId'];
$bot_username = $config['bot_username'];

try {
    // Create Telegram API object
    $telegram = new Longman\TelegramBot\Telegram($bot_api_key, $bot_username);

    // Handle telegram webhook request
    $telegram->handle();
} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    // echo $e->getMessage();
}