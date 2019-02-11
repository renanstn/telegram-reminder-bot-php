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
    $regex_hour     = '/\d{1,2}[h|:]\d{2}/m';
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
            'hour'      => str_replace('h', ':', $hour[0]),
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

function saveReminder($chatId, $data, $conn) {

    extract($data);

    $date_time = format_date_hour($date, $hour);
    $sql = "
        INSERT INTO reminders (chat_id, date_hour, content) 
        VALUES (:chat_id, :data_hora, :reminder)
    ";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':chat_id'      => $chatId,
        ':data_hora'    => $date_time,
        ':reminder'     => $reminder
    ]);
}

function format_date_hour($date, $hour) {

    $date = explode("/", $date);    
	return $date[2]."-".$date[1]."-".$date[0]." ".$hour.":00";

}

function checkReminders($date, $initialTime, $finalTime, $conn) {

    // Formatar data e hora
    $date_time_init     = $date.' '.$initialTime.':00';
    $date_time_final    = $date.' '.$finalTime.':00';

    $sql = "
        SELECT 
            chat_id,
            content
        FROM 
            reminders
        WHERE 1=1
            AND date_hour BETWEEN '$date_time_init' AND '$date_time_final'
    ";

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $result;
}

function saveLog($reminders, $initialTime, $finalTime) {

    $dateTime   = new DateTime();
    $hora_atual = $dateTime->format('d/m/Y H:i:s');
    $count      = count($reminders);
    $log        = "Script executado as $hora_atual. ";
    $log        .= "Lembretes encontrados das $initialTime até $finalTime: $count. \n";
    file_put_contents('log.txt', $log, FILE_APPEND);
}

function getLog($lines=40) {

    $log_last_lines = "";
    $file = file('log.txt');
    for ($i = max(0, count($file) -$lines); $i < count($file); $i++) {
        $log_last_lines .= $file[$i];
    }
    return $log_last_lines;
}