<?php
$host = 'localhost';
$db = 'monitoramento_OLTs';
$user = 'vinicius';
$pass = 'A132546b';


$mysqli = new mysqli($host, $user, $pass, $db);
if($mysqli->connect_errno){
    die("Erro de conexão no banco de dados:" . $mysqli->connect_errno);
}
?>