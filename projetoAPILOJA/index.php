<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

// Função para obter o URL base
function getBaseUrl() {
    // Protocolo
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    // Host
    $host = $_SERVER['HTTP_HOST'];
    // Caminho
    $scriptName = dirname($_SERVER['SCRIPT_NAME']);
    
    return "$protocol://$host$scriptName/";
}

// Obtém a URL base
$baseUrl = getBaseUrl();

// Define o link dinâmico
$endpointClienteUrl = $baseUrl . 'Controller/controllerCliente.php'; //Cliente
$endpointPedidoUrl = $baseUrl . 'Controller/controllerPedido.php'; //Pedido
$endpointPedidoProdutoUrl = $baseUrl . 'Controller/controllerPedidoProduto.php'; //PedidoProduto
$endpointProdutoUrl = $baseUrl . 'Controller/controllerProduto.php'; //Produto

// Cria o array com o formato desejado
$os = null;

if (php_uname('s') === 'Windows') {
    $os = 'Windows';
}

$resposta = [
    'End points:' => [
        "Cliente:" => [
            "Url:" => $endpointClienteUrl,
            "GET:" => [
                "Tipos de busca:" => [
                "Busca unica por id: " => "Envie o id por body ou coloque ?id=5 no link para buscar por id",
                "Busca geral:" => "Apenas entre com o link sem nenhum id, ou com o body vazio para dar get em todos os usuários."
            ]],
            "POST" => [
                    "Envio por body:" => [
                        "nome" => "Nome do Cliente",
                        "endereco" => "Endereço do Cliente",
                        "cpf" => "CPF do Cliente",
                        "telefone" => "Telefone do Cliente",
                        "email" => "Email do Cliente",
                        "datanascimento" => "Data de Nascimento do Cliente",
                    ],
                    "Envio por link:" => [
                        "Link:" => "?nome=a&endereco=a&cpf=1&telefone=1&email=a&datanascimento=1"
                    ],
                ],
            "PUT" => [
                "Tipos de atualização:" => [
                "Envio por body:" => [
                    "id" => "id de seleção",
                    "nome" => "Nome do Cliente",
                    "endereco" => "Endereço do Cliente",
                    "cpf" => "CPF do Cliente",
                    "telefone" => "Telefone do Cliente",
                    "email" => "Email do Cliente",
                    "datanascimento" => "Data de Nascimento do Cliente"
                ],
                "Envio por link:" => [
                    "Link:" => "?id=1&nome=a&endereco=a&cpf=1&telefone=1&email=a&datanascimento=1"
                ],
            ],
            "PATCH" => [
                "Tipos de atualização:" => [
                    "Envio por body:" => [
                        "id=1"
                    ],
                    "Envio por link:" => [
                        "?id=1"
                    ]
                ],
            ],
        ],
        "Pedido:" => $endpointPedidoUrl,
        "Pedido_Produto:" => $endpointPedidoProdutoUrl,
        "Produto:" => $endpointProdutoUrl
    ],
    'Server Info:' => [
        'Método de requisição:' => $_SERVER['REQUEST_METHOD'],
        'Plataforma de execução:' => $os,
        'Link:' => $_SERVER['REQUEST_URI'],
        'Protocolo:' => $_SERVER['SERVER_PROTOCOL']
        ]
    ]
];

// Converte o array em JSON e o exibe
echo json_encode($resposta, JSON_PRETTY_PRINT);