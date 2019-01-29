<?php
require __DIR__ . '/vendor/autoload.php';

$config = parse_ini_file("config.ini");

$bot_api_key  = $config['botId'];
$bot_username = $config['bot_username'];
$hook_url     = $config['hook_url'];

try {
    // Create Telegram API object
    $telegram = new Longman\TelegramBot\Telegram($bot_api_key, $bot_username);

    // Set webhook
    $result = $telegram->setWebhook($hook_url);
    if ($result->isOk()) {
        echo $result->getDescription();
    }
} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    // log telegram errors
    // echo $e->getMessage();
}