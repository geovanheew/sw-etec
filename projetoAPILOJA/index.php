<?php 
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

// Cria o array com o formato desejado
$resposta = [
    'End points:' => [
        
    ],
    'Server Info:' => [
        'Método de requisição:' => $_SERVER['REQUEST_METHOD'],
        'Plataforma de execução:' => $_SERVER['HTTP_SEC_CH_UA_PLATFORM'],
        'Link:' => $_SERVER['HTTP_REFERER'],
        'Protocolo:' => $_SERVER['SERVER_PROTOCOL']
        
    ]
];

// Converte o array em JSON e o exibe
echo json_encode($resposta, JSON_PRETTY_PRINT);