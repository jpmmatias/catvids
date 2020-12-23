<?php 
ob_start();

session_start();

date_default_timezone_set('America/Sao_Paulo');

try {
    $conn=new PDO("mysql:dbname=catvids;host=localhost","root","");
    $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);
} catch (PDOException $err) {
    echo "Connection failed" . $err->getMessage();
}