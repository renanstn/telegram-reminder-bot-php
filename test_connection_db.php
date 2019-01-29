<?php

$config     = parse_ini_file("./config.ini");
$server     = $config['server'];
$username   = $config['username'];
$password   = $config['password'];

try {
    $conn = new PDO("mysql:host=$server;dbname=$db", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Foi";
} catch(PDOException $e) {
    echo "Foi não";
}
