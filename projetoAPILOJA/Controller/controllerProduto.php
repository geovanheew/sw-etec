<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

require_once '../Model/ProdutoModel.php';
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

// Lê o corpo da requisição e converte as chaves para minúsculas
$input = json_decode(file_get_contents('php://input'), true);
$input = arrayKeysToLower($input);

// Início da sessão
switch ($_SERVER['REQUEST_METHOD']) {
    case "GET":
        $id = getIdFromRequest();
        $product = new Product();
        
        if ($id) {
            $productData = $product->get($id);

            if ($productData) {
                $retorno = [
                    'Produtos' => [$productData],
                    'Informacoes' => [
                        'Método de requisição' => $_SERVER['REQUEST_METHOD'],
                        'Resposta' => json_decode(Resposta::construirResp(200)->exibirResposta(), true)
                    ]
                ];
            } else {
                $retorno = [
                    'Produtos' => [],
                    'Informacoes' => [
                        'Método de requisição' => $_SERVER['REQUEST_METHOD'],
                        'Resposta' => json_decode(Resposta::construirResp(404)->exibirResposta(), true)
                    ]
                ];
            }
        } else {
            $productsData = $product->getAll();

            if (!empty($productsData)) {
                $retorno = [
                    'Produtos' => $productsData,
                    'Informacoes' => [
                        'Método de requisição' => $_SERVER['REQUEST_METHOD'],
                        'Resposta' => json_decode(Resposta::construirResp(200)->exibirResposta(), true)
                    ]
                ];
            } else {
                $retorno = [
                    'Produtos' => [],
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
        if (isset($input['nome']) && isset($input['descricao']) && isset($input['qtd']) && isset($input['marca']) && isset($input['preco']) && isset($input['validade'])) {
            $product = new Product(
                null,
                htmlspecialchars($input['nome']),
                htmlspecialchars($input['descricao']),
                intval($input['qtd']),
                htmlspecialchars($input['marca']),
                floatval($input['preco']),
                htmlspecialchars($input['validade'])
            );

            $product->post();

            $retorno = [
                'Produtos' => [
                    'id' => $product->id,
                    'nome' => $product->nome,
                    'descricao' => $product->descricao,
                    'qtd' => $product->qtd,
                    'marca' => $product->marca,
                    'preco' => $product->preco,
                    'validade' => $product->validade
                ],
                'Informacoes' => [
                    'Método de requisição' => $_SERVER['REQUEST_METHOD'],
                    'Resposta' => json_decode(Resposta::construirResp(201)->exibirResposta(), true)
                ]
            ];
            http_response_code(201); // Define o código de resposta HTTP para criação
        } else {
            $retorno = [
                'Produtos' => [],
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
                'Produtos' => [],
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
        if (isset($input['nome']) && isset($input['descricao']) && isset($input['qtd']) && isset($input['marca']) && isset($input['preco']) && isset($input['validade'])) {
            $product = new Product(
                $id,
                htmlspecialchars($input['nome']),
                htmlspecialchars($input['descricao']),
                intval($input['qtd']),
                htmlspecialchars($input['marca']),
                floatval($input['preco']),
                htmlspecialchars($input['validade'])
            );

            $product->update();

            $retorno = [
                'Produtos' => [
                    'id' => $product->id,
                    'nome' => $product->nome,
                    'descricao' => $product->descricao,
                    'qtd' => $product->qtd,
                    'marca' => $product->marca,
                    'preco' => $product->preco,
                    'validade' => $product->validade
                ],
                'Informacoes' => [
                    'Método de requisição' => $_SERVER['REQUEST_METHOD'],
                    'Resposta' => json_decode(Resposta::construirResp(200)->exibirResposta(), true)
                ]
            ];
            http_response_code(200);
        } else {
            $retorno = [
                'Produtos' => [],
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
            $product = new Product($id);
            
            $product->toggleStatus();
            
            $retorno = [
                'Produtos' => [
                    'id' => $product->id,
                    'status' => $product->status
                ],
                'Informacoes' => [
                    'Método de requisição' => $_SERVER['REQUEST_METHOD'],
                    'Resposta' => json_decode(Resposta::construirResp(200)->exibirResposta(), true)
                ]
            ];
            http_response_code(200);
        } else {
            $retorno = [
                'Produtos' => [],
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
            'Produtos' => [],
            'Informacoes' => [
                'Error' => 'Método HTTP não suportado'
            ]
        ];

        echo json_encode($resposta, JSON_PRETTY_PRINT);
        break;
}
