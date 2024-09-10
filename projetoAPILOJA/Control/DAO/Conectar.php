<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

require_once __DIR__ . '/../../Model/Utilidades/Resposta.php';

function Conectar() {
    $host = 'mysqlapis-mysqldetestes.c.aivencloud.com';
    $usuario = 'avnadmin';
    $senha = 'AVNS_A4VgkDPzFiFHyWs2nir';
    $bd = 'LojaApi';
    $porta = '24696';

    // Criar a conexão com SSL
    $conexao = mysqli_init();
    mysqli_ssl_set($conexao, NULL, NULL, __DIR__.'/ca.pem', NULL, NULL);
    mysqli_real_connect($conexao, $host, $usuario, $senha, $bd, $porta, NULL, MYSQLI_CLIENT_SSL);
    
    if (mysqli_connect_errno()) {
        $retorno = new Resposta(500, "Falha ao conectar: " . mysqli_connect_error());
        echo $retorno->exibirResposta();
        return null;
    }

    return $conexao;
}

function Desconectar($conexao) {
    if ($conexao) {
        mysqli_close($conexao);
        $retorno = Resposta::construirResp(204);
    } else {
        $retorno = Resposta::construirResp(400);
    }
    return $retorno->exibirResposta();
}