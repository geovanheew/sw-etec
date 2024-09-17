<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

require_once '../Model/PedidosProdutosModel.php';
require_once '../Model/Utilidades/Resposta.php';

// Função para converter as chaves do array para minúsculas
function arrayKeysToLower($array)
{
    $result = [];
    foreach ($array as $key => $value) {
        $result[strtolower($key)] = is_array($value) ? arrayKeysToLower($value) : $value;
    }
    return $result;
}

// Função para obter IDs da URL ou do corpo da requisição
function getIdsFromRequest()
{
    $id_pedido = isset($_GET['id_pedido']) ? intval($_GET['id_pedido']) : null;
    $id_produto = isset($_GET['id_produto']) ? intval($_GET['id_produto']) : null;
    $input = json_decode(file_get_contents('php://input'), true);
    if (is_array($input)) {
        $input = arrayKeysToLower($input);
        $id_pedido = $id_pedido ?: (isset($input['id_pedido']) ? intval($input['id_pedido']) : null);
        $id_produto = $id_produto ?: (isset($input['id_produto']) ? intval($input['id_produto']) : null);
    }
    return [$id_pedido, $id_produto];
}

// Lê o corpo da requisição e converte as chaves para minúsculas
$input = json_decode(file_get_contents('php://input'), true);
$input = arrayKeysToLower($input);

// Início da sessão
switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        list($id_pedido, $id_produto) = getIdsFromRequest();
        $pedidoProduto = new PedidoProduto();

        if ($id_pedido && $id_produto) {
            $resultado = $pedidoProduto->get($id_pedido, $id_produto);
            $retorno['Pedidos_Produtos'] = $resultado ? [$resultado] : [];
            $retorno['Informacoes'] = [
                'Resposta' => [
                    'codigo' => $resultado ? 200 : 404,
                    'mensagem' => $resultado ? 'Sucesso!' : 'Nenhum pedido produto encontrado para os IDs fornecidos.'
                ]
            ];
        } elseif ($id_pedido) {
            $resultado = $pedidoProduto->getByPedido($id_pedido);
            $retorno['Pedidos_Produtos'] = $resultado;
            $retorno['Informacoes'] = [
                'Resposta' => [
                    'codigo' => !empty($resultado) ? 200 : 404,
                    'mensagem' => !empty($resultado) ? 'Sucesso!' : 'Nenhum pedido produto encontrado para o ID fornecido.'
                ]
            ];
        } elseif ($id_produto) {
            $resultado = $pedidoProduto->getByProduto($id_produto);
            $retorno['Pedidos_Produtos'] = $resultado;
            $retorno['Informacoes'] = [
                'Resposta' => [
                    'codigo' => !empty($resultado) ? 200 : 404,
                    'mensagem' => !empty($resultado) ? 'Sucesso!' : 'Nenhum pedido produto encontrado para o ID fornecido.'
                ]
            ];
        } else {
            $resultado = $pedidoProduto->getAll();
            $retorno['Pedidos_Produtos'] = $resultado;
            $retorno['Informacoes'] = [
                'Resposta' => [
                    'codigo' => 200,
                    'mensagem' => 'Sucesso!'
                ]
            ];
        }
        http_response_code($retorno['Informacoes']['Resposta']['codigo']);
        echo json_encode($retorno, JSON_PRETTY_PRINT);
        break;

        case 'POST':
            if (isset($input['id_pedido'], $input['id_produto'], $input['qtd'])) {
                // Cria o objeto sem definir o status
                $pedidoProduto = new PedidoProduto(
                    intval($input['id_pedido']),
                    intval($input['id_produto']),
                    intval($input['qtd'])
                );
                $pedidoProduto->post();
                
                // Prepara a resposta com o status obtido
                $retorno = [
                    'Pedidos_Produtos' => [
                        'id_pedido' => $pedidoProduto->id_pedido,
                        'id_produto' => $pedidoProduto->id_produto,
                        'qtd' => $pedidoProduto->qtd,
                        'status' => $pedidoProduto->status
                    ],
                    'Informacoes' => [
                        'Resposta' => [
                            'codigo' => 201,
                            'mensagem' => 'Criado com sucesso!'
                        ]
                    ]
                ];
                http_response_code(201);
            } else {
                $retorno = [
                    'Pedidos_Produtos' => [],
                    'Informacoes' => [
                        'Resposta' => [
                            'codigo' => 400,
                            'mensagem' => 'Dados incompletos ou inválidos.'
                        ]
                    ]
                ];
                http_response_code(400);
            }
            echo json_encode($retorno, JSON_PRETTY_PRINT);
            break;        


    case 'PUT':
        list($id_pedido, $id_produto) = getIdsFromRequest();
        if ($id_pedido && $id_produto && isset($input['qtd'])) {
            $pedidoProduto = new PedidoProduto(
                $id_pedido,
                $id_produto,
                intval($input['qtd'])
            );
            $pedidoProduto->updateQuantity();
            $retorno = [
                'Pedidos_Produtos' => [
                    'id_pedido' => $pedidoProduto->id_pedido,
                    'id_produto' => $pedidoProduto->id_produto,
                    'qtd' => $pedidoProduto->qtd
                ],
                'Informacoes' => [
                    'Resposta' => [
                        'codigo' => 200,
                        'mensagem' => 'Quantidade atualizada com sucesso!'
                    ]
                ]
            ];
            http_response_code(200);
        } else {
            $retorno = [
                'Pedidos_Produtos' => [],
                'Informacoes' => [
                    'Resposta' => [
                        'codigo' => 400,
                        'mensagem' => 'Dados incompletos ou inválidos.'
                    ]
                ]
            ];
            http_response_code(400);
        }
        echo json_encode($retorno, JSON_PRETTY_PRINT);
        break;


    case 'PATCH':
        list($id_pedido, $id_produto) = getIdsFromRequest();
        if ($id_pedido && $id_produto) {
            $pedidoProduto = new PedidoProduto($id_pedido, $id_produto);
            $pedidoProduto->toggleStatus();
            $retorno = [
                'Pedidos_Produtos' => [
                    'id_pedido' => $pedidoProduto->id_pedido,
                    'id_produto' => $pedidoProduto->id_produto,
                    'status' => $pedidoProduto->status
                ],
                'Informacoes' => [
                    'Resposta' => [
                        'codigo' => 200,
                        'mensagem' => 'Status alterado com sucesso!'
                    ]
                ]
            ];
            http_response_code(200);
        } else {
            $retorno = [
                'Pedidos_Produtos' => [],
                'Informacoes' => [
                    'Resposta' => [
                        'codigo' => 400,
                        'mensagem' => 'Dados incompletos ou inválidos.'
                    ]
                ]
            ];
            http_response_code(400);
        }
        echo json_encode($retorno, JSON_PRETTY_PRINT);
        break;


    default:
        $retorno = [
            'Pedidos_Produtos' => [],
            'Informacoes' => [
                'Resposta' => [
                    'codigo' => 405,
                    'mensagem' => 'Método não permitido.'
                ]
            ]
        ];
        http_response_code(405);
        echo json_encode($retorno, JSON_PRETTY_PRINT);
        break;
}
