<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

// Função para obter o URL base
function getBaseUrl()
{
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
            "Tipos de requisição:" => [
                "GET:" => [
                    "Tipos de busca:" => [
                        "Busca unica por id:" => "Envie o id por body ou coloque ?id=5 no link para buscar por id",
                        "Busca geral:" => "Apenas entre com o link sem nenhum id, ou com o body vazio para dar get em todos os usuários."
                    ]
                ],
                "POST" => [
                    "Envio por body:" => [
                        "nome" => "Nome do Cliente",
                        "endereco" => "Endereço do Cliente",
                        "cpf" => "CPF do Cliente",
                        "telefone" => "Telefone do Cliente",
                        "email" => "Email do Cliente",
                        "datanascimento" => "Data de Nascimento do Cliente"
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
                ],
                "PATCH" => [
                    "Tipos de atualização de status:" => [
                        "Envio por body:" => [
                            "id=1"
                        ],
                        "Envio por link:" => [
                            "?id=1"
                        ]
                    ],
                ],
            ]

        ],
        "Pedido:" => [
            "Tipos de requisição:" => [
                "Url:" => $endpointPedidoUrl,
                "GET:" => [
                    "Tipos de busca:" => [
                        "Busca unica por id:" => "Envie o id por body ou coloque ?id=5 no link para buscar por id",
                        "Busca geral:" => "Apenas entre com o link sem nenhum id, ou com o body vazio para dar get em todos os pedidos."
                    ],
                ],
                "POST:" => [
                    "Envio por body:" => [
                        "id_cliente" => "ID do Cliente que fez o pedido",
                        "data" => "Data do Pedido"
                    ]
                ],
                "PUT:" => [
                    "Tipos de atualização:" => [
                        "Envio por body:" => [
                            "id" => "id do pedido que deseja atualizar",
                            "id_cliente" => "ID do Cliente",
                            "data" => "Data do Pedido"
                        ],
                        "Envio por link:" => [
                            "Link:" => "?id=1&id_cliente=2&data=2024-09-27"
                        ]
                    ]
                ],
                "PATCH:" => [
                    "Tipos de atualização de status:" => [
                        "Envio por body:" => [
                            "id" => "id do pedido"
                        ],
                        "Envio por link:" => [
                            "Link:" => "?id=1"
                        ]
                    ]
                ]
            ]
        ],
        "Pedido_Produto:" => [
            "Url:" => $endpointPedidoProdutoUrl,
            "Tipos de requisição:" => [
                "GET:" => [
                    "Tipos de busca:" => [
                        "Busca unica por id_pedido e id_produto:" => "Envie os IDs por body ou coloque ?id_pedido=5&id_produto=10 no link para buscar.",
                        "Busca por id_pedido:" => "Coloque ?id_pedido=5 para buscar todos os produtos de um pedido.",
                        "Busca por id_produto:" => "Coloque ?id_produto=10 para buscar todos os pedidos de um produto.",
                        "Busca geral:" => "Apenas entre com o link sem nenhum id, ou com o body vazio para dar get em todos os pedidos produtos."
                    ]
                ],
                "POST:" => [
                    "Envio por body:" => [
                        "id_pedido" => "ID do Pedido",
                        "id_produto" => "ID do Produto",
                        "qtd" => "Quantidade do Produto no Pedido"
                    ]
                ],
                "PUT:" => [
                    "Tipos de atualização:" => [
                        "Envio por body:" => [
                            "id_pedido" => "ID do Pedido",
                            "id_produto" => "ID do Produto",
                            "qtd" => "Nova quantidade do Produto no Pedido"
                        ],
                        "Envio por link:" => [
                            "Link:" => "?id_pedido=1&id_produto=1&qtd=10"
                        ]
                    ]
                ],
                "PATCH:" => [
                    "Tipos de atualização de status:" => [
                        "Envio por body:" => [
                            "id_pedido" => "ID do Pedido",
                            "id_produto" => "ID do Produto"
                        ],
                        "Envio por link:" => [
                            "Link:" => "?id_pedido=1&id_produto=1"
                        ]
                    ]
                ]
            ]
        ],
        "Produto:" => [
            "Url:" => $endpointProdutoUrl,
            "Tipos de requisição:" => [
                "GET:" => [
                    "Tipos de busca:" => [
                        "Busca unica por id:" => "Envie o id por body ou coloque ?id=5 no link para buscar por id.",
                        "Busca geral:" => "Apenas entre com o link sem nenhum id, ou com o body vazio para dar get em todos os produtos."
                    ]
                ],
                "POST:" => [
                    "Envio por body:" => [
                        "nome" => "Nome do Produto",
                        "descricao" => "Descrição do Produto",
                        "qtd" => "Quantidade disponível",
                        "marca" => "Marca do Produto",
                        "preco" => "Preço do Produto",
                        "validade" => "Data de Validade do Produto"
                    ],
                    "Envio por link:" => [
                        "Link:" => "?nome=ProdutoX&descricao=DescriçãoX&qtd=10&marca=MarcaX&preco=99.99&validade=2024-12-31"
                    ]
                ],
                "PUT:" => [
                    "Tipos de atualização:" => [
                        "Envio por body:" => [
                            "id" => "ID do Produto a ser atualizado",
                            "nome" => "Novo Nome do Produto",
                            "descricao" => "Nova Descrição do Produto",
                            "qtd" => "Nova Quantidade disponível",
                            "marca" => "Nova Marca do Produto",
                            "preco" => "Novo Preço do Produto",
                            "validade" => "Nova Data de Validade do Produto"
                        ],
                        "Envio por link:" => [
                            "Link:" => "?id=1&nome=ProdutoX&descricao=DescriçãoX&qtd=10&marca=MarcaX&preco=99.99&validade=2024-12-31"
                        ]
                    ]
                ],
                "PATCH:" => [
                    "Tipos de atualização de status:" => [
                        "Envio por body:" => [
                            "id" => "ID do Produto"
                        ],
                        "Envio por link:" => [
                            "Link:" => "?id=1"
                        ]
                    ]
                ]
            ]
        ],
    ],
    'Server Info:' => [
        'Método de requisição:' => $_SERVER['REQUEST_METHOD'],
        'Plataforma de execução:' => $os,
        'Link:' => $_SERVER['REQUEST_URI'],
        'Protocolo:' => $_SERVER['SERVER_PROTOCOL']
    ]
];

// Converte o array em JSON e o exibe
echo json_encode($resposta, JSON_PRETTY_PRINT);
