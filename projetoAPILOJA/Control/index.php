<?php 
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

// Obtém o método HTTP da requisição
$metodo = $_SERVER['REQUEST_METHOD'];

// Cria o array com o formato desejado
$resposta = [
    'REQUISICAO' => $metodo
];

// Converte o array em JSON e o exibe
echo json_encode($resposta, JSON_PRETTY_PRINT);
