<?php
$host = '172.31.255.51'; //MGP Telecom
$db = 'monitoramento_OLTs';
$user = 'vinicius';
$pass = 'A132546b';
/*
$host = '172.31.255.6'; //MS Telecom
$user = 'monitoramento'; //MS Telecom
$pass = 'Av!n!0306b'; // MS Telecom
*/

$mysqli = new mysqli('127.0.0.1', 'vinicius', 'A132546b', 'monitoramento_OLTs');
if($mysqli->connect_errno){
    die("Erro de conexão no banco de dados:" . $mysqli->connect_errno);
}
?>