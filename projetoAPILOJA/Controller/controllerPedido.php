<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

require_once '../Model/PedidoModel.php';
require_once '../Model/Utilidades/Resposta.php';

// Função para converter as chaves do array para minúsculas
function arrayKeysToLower($array) {
    $result = [];
    foreach ($array as $key => $value) {
        if (is_array($value)) {
            $result[strtolower($key)] = arrayKeysToLower($value);
        } else {
            $result[strtolower($key)] = $value;
        }
    }
    return $result;
}

// Função para obter o ID da URL ou do corpo da requisição
function getIdFromRequest() {
    global $input;
    $id = isset($_GET['id']) ? intval($_GET['id']) : null;
    if (!$id && isset($input['id']) && is_numeric($input['id'])) {
        $id = intval($input['id']);
    }
    return $id;
}

// Lê o corpo da requisição
$input = json_decode(file_get_contents('php://input'), true);

// Verifica se $input é um array e não está vazio
if (is_array($input) && !empty($input)) {
    $input = arrayKeysToLower($input);
} else {
    $input = []; // Se estiver vazio, define como um array vazio
}

// Início da sessão
switch ($_SERVER['REQUEST_METHOD']) {
    case "GET":
        $id = getIdFromRequest();
        $pedido = new Pedido();

        if ($id) {
            $pedidoData = $pedido->get($id);

            if ($pedidoData) {
                $retorno = [
                    'Pedidos' => [$pedidoData],
                    'Informacoes' => [
                        'Método de requisição' => $_SERVER['REQUEST_METHOD'],
                        'Resposta' => json_decode(Resposta::construirResp(200)->exibirResposta(), true)
                    ]
                ];
            } else {
                $retorno = [
                    'Pedidos' => [],
                    'Informacoes' => [
                        'Método de requisição' => $_SERVER['REQUEST_METHOD'],
                        'Resposta' => json_decode(Resposta::construirResp(404)->exibirResposta(), true)
                    ]
                ];
            }
        } else {
            $pedidosData = $pedido->getAll();

            if (is_array($pedidosData) && !empty($pedidosData)) {
                $retorno = [
                    'Pedidos' => $pedidosData,
                    'Informacoes' => [
                        'Método de requisição' => $_SERVER['REQUEST_METHOD'],
                        'Resposta' => json_decode(Resposta::construirResp(200)->exibirResposta(), true)
                    ]
                ];
            } else {
                $retorno = [
                    'Pedidos' => [],
                    'Informacoes' => [
                        'Método de requisição' => $_SERVER['REQUEST_METHOD'],
                        'Resposta' => json_decode(Resposta::construirResp(404)->exibirResposta(), true)
                    ]
                ];
            }
        }
        echo json_encode($retorno, JSON_PRETTY_PRINT);
        break;

    case "POST":
        // Verifica se os dados esperados estão presentes
        if (isset($input['id_cliente']) && isset($input['data'])) {
            $pedido = new Pedido(
                null, // id_pedido será gerado automaticamente
                intval($input['id_cliente']),
                htmlspecialchars($input['data']),
                null // status não é passado no POST
            );

            $pedido->post();

            $retorno = [
                'Pedidos' => [
                    'id_pedido' => $pedido->id_pedido,
                    'id_cliente' => $pedido->id_cliente,
                    'data' => $pedido->data,
                    'status' => $pedido->status
                ],
                'Informacoes' => [
                    'Método de requisição' => $_SERVER['REQUEST_METHOD'],
                    'Resposta' => json_decode(Resposta::construirResp(201)->exibirResposta(), true)
                ]
            ];
            http_response_code(201); // Define o código de resposta HTTP para criação
        } else {
            $retorno = [
                'Pedidos' => [],
                'Informacoes' => [
                    'Método de requisição' => $_SERVER['REQUEST_METHOD'],
                    'Resposta' => json_decode(Resposta::construirResp(400)->exibirResposta(), true)
                ]
            ];
            http_response_code(400); // Define o código de resposta HTTP para solicitação inválida
        }

        echo json_encode($retorno, JSON_PRETTY_PRINT);
        break;

        case "PUT":
            $id = getIdFromRequest();
            if ($id <= 0) {
                $retorno = [
                    'Pedidos' => [],
                    'Informacoes' => [
                        'Método de requisição' => $_SERVER['REQUEST_METHOD'],
                        'Resposta' => json_decode(Resposta::construirResp(400)->exibirResposta(), true)
                    ]
                ];
                http_response_code(400);
                echo json_encode($retorno, JSON_PRETTY_PRINT);
                break;
            }
    
            // Verifica se os dados esperados estão presentes
            if (isset($input['id_cliente']) && isset($input['data'])) {
                $pedido = new Pedido(
                    $id,
                    intval($input['id_cliente']),
                    htmlspecialchars($input['data'])
                    // O status não é necessário aqui
                );
    
                $pedido->update();
    
                // Recarrega o pedido para garantir que o status atualizado seja retornado
                $pedidoAtualizado = $pedido->get($id);
    
                $retorno = [
                    'Pedidos' => [
                        'id_pedido' => $pedidoAtualizado['id_pedido'],
                        'id_cliente' => $pedidoAtualizado['id_cliente'],
                        'data' => $pedidoAtualizado['data'],
                        'status' => $pedidoAtualizado['status'] // Inclua o status na resposta
                    ],
                    'Informacoes' => [
                        'Método de requisição' => $_SERVER['REQUEST_METHOD'],
                        'Resposta' => json_decode(Resposta::construirResp(200)->exibirResposta(), true)
                    ]
                ];
                http_response_code(200);
            } else {
                $retorno = [
                    'Pedidos' => [],
                    'Informacoes' => [
                        'Método de requisição' => $_SERVER['REQUEST_METHOD'],
                        'Resposta' => json_decode(Resposta::construirResp(400)->exibirResposta(), true)
                    ]
                ];
                http_response_code(400);
            }
    
            echo json_encode($retorno, JSON_PRETTY_PRINT);
            break;

    case "PATCH":
        $id = getIdFromRequest();
        if ($id) {
            $pedido = new Pedido($id);

            $pedido->toggleStatus();

            $retorno = [
                'Pedidos' => [
                    'id_pedido' => $pedido->id_pedido,
                    'status' => $pedido->status
                ],
                'Informacoes' => [
                    'Método de requisição' => $_SERVER['REQUEST_METHOD'],
                    'Resposta' => json_decode(Resposta::construirResp(200)->exibirResposta(), true)
                ]
            ];
            http_response_code(200);
        } else {
            $retorno = [
                'Pedidos' => [],
                'Informacoes' => [
                    'Método de requisição' => $_SERVER['REQUEST_METHOD'],
                    'Resposta' => json_decode(Resposta::construirResp(400)->exibirResposta(), true)
                ]
            ];
            http_response_code(400);
        }

        echo json_encode($retorno, JSON_PRETTY_PRINT);
        break;

    default:
        $resposta = [
            'Pedidos' => [],
            'Informacoes' => [
                'Error' => 'Método HTTP não suportado'
            ]
        ];

        echo json_encode($resposta, JSON_PRETTY_PRINT);
        break;
}