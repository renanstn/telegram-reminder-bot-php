<?php

$config     = parse_ini_file("./config.ini");
$server     = $config['server'];
$username   = $config['username'];
$password   = $config['password'];
$db         = $config['db'];

try {
    // $conn = new PDO("mysql:host=$server;dbname=$db", $username, $password);
    // $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn = pg_connect("postgres://$username:$password@$server/$db");
} catch(PDOException $e) {
    echo $e;
}
