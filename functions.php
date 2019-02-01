<?php

function sendMessage($chatId, $text) {

    $message = urlencode($text);
    $url = $GLOBALS[website]."/sendMessage?chat_id=$chatId&text=$message";
    file_get_contents($url);
}

function recognizer($text) {

    $return = false;

    $regex_date     = '/\d{2}\/\d{2}\/\d{4}/m';
    $regex_tomorrow = '/amanhã|amanha/m';
    $regex_today    = '/hoje/m';
    $regex_hour     = '/\d{1,2}:\d{2}/m';
    $regex_msg      = '/^[^,]*/m';

    $has_date       = preg_match($regex_date, $text, $date);
    $has_tomorrow   = preg_match($regex_tomorrow, $text);
    $has_today      = preg_match($regex_today, $text);
    $has_hour       = preg_match($regex_hour, $text, $hour);
    $has_reminder   = preg_match($regex_msg, $text, $reminder);

    // Transformar palavras chave 'hoje/amanhã' em datas
    if ($has_tomorrow) {
        $date       = what_day_is_tomorrow();
        $has_date   = true;

    } else if ($has_today) {
        $date       = what_day_is_today();
        $has_date   = true;
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

    $date       = new DateTime();
    $date       = $date->modify("+1 day");
    $return[0]  = $date->format('d/m/Y');
    return $return;
}

function what_day_is_today() {

    $date       = new DateTime();
    $return[0]  = $date->format('d/m/Y');
    return $return;
}

function saveReminder($chatId, $data) {

    require "connect.php";
    extract($data);

    $date_time = format_date_hour($date, $hour);
    $sql    = "INSERT INTO reminders (chat_id, date_hour, content) VALUES (:chat_id, :data_hora, :reminder)";
    $stmt   = $conn->prepare($sql);
    $teste = $stmt->execute([
        ':chat_id'      => $chatId,
        ':data_hora'    => $date_time,
        ':reminder'     => $reminder
    ]);
    sendMessage($chatId, $teste);
}

function format_date_hour($date, $hour) {

    $date = explode("/", $date);    
	return $date[2]."-".$date[1]."-".$date[0]." ".$hour.":00";

}

function hourIsValid($hour) {

    return substr($hour, -1) == "0";
}
