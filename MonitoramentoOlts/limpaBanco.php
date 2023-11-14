<?php

function limpaBanco(){
    require_once("conexao.php");
    $execQuery = $mysqli->query("DELETE FROM onu WHERE TIMESTAMPDIFF(HOUR, ult_atualizacao, NOW()) >= 5");

    if ($execQuery) {
        // Consulta executada com sucesso
        $numRowsDeleted = $mysqli->affected_rows;
        echo "Foram apagadas {$numRowsDeleted} linhas.";
    } else {
        // Erro na execução da consulta
        echo "Erro ao executar a consulta: " . $mysqli->error;
    }
}


limpaBanco();