<?php 
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

// Cria o array com o formato desejado

$os = null;

if ($_SERVER['REQUEST_URI'] == "C:\\Windows") {
    $os = 'Windows';
}


$resposta = [
    'End points:' => [
        "Cliente:" => '/sw-etec/projetoAPILOJA/Controller/controllerCliente.php?id=1'
    ],
    'Server Info:' => [
        'Método de requisição:' => $_SERVER['REQUEST_METHOD'],
        'Plataforma de execução:' => $os,
        'Link:' => $_SERVER['REQUEST_URI'],
        'Protocolo:' => $_SERVER['SERVER_PROTOCOL']
    ]
];

// Converte o array em JSON e o exibe
echo json_encode($resposta  , JSON_PRETTY_PRINT);