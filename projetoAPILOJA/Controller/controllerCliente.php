<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

require_once '../Model/ClienteModel.php';
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
        $cliente = new Cliente();
        
        if ($id) {
            $clienteData = $cliente->get($id);

            if ($clienteData) {
                $retorno = [
                    'Clientes' => [$clienteData],
                    'Informacoes' => [
                        'Método de requisição' => $_SERVER['REQUEST_METHOD'],
                        'Resposta' => json_decode(Resposta::construirResp(200)->exibirResposta(), true)
                    ]
                ];
            } else {
                $retorno = [
                    'Clientes' => [],
                    'Informacoes' => [
                        'Método de requisição' => $_SERVER['REQUEST_METHOD'],
                        'Resposta' => json_decode(Resposta::construirResp(404)->exibirResposta(), true)
                    ]
                ];
            }
        } else {
            $clientesData = $cliente->getAll();

            if (!empty($clientesData)) {
                $retorno = [
                    'Clientes' => $clientesData,
                    'Informacoes' => [
                        'Método de requisição' => $_SERVER['REQUEST_METHOD'],
                        'Resposta' => json_decode(Resposta::construirResp(200)->exibirResposta(), true)
                    ]
                ];
            } else {
                $retorno = [
                    'Clientes' => [],
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
        if (isset($input['nome']) && isset($input['endereco']) && isset($input['cpf']) && isset($input['telefone']) && isset($input['email']) && isset($input['datanascimento'])) {
            $cliente = new Cliente(
                null,
                htmlspecialchars($input['nome']),
                htmlspecialchars($input['endereco']),
                htmlspecialchars($input['cpf']),
                htmlspecialchars($input['telefone']),
                htmlspecialchars($input['email']),
                htmlspecialchars($input['datanascimento'])
            );

            $cliente->post();

            $retorno = [
                'Clientes' => [
                    'id' => $cliente->id,
                    'nome' => $cliente->nome,
                    'endereco' => $cliente->endereco,
                    'cpf' => $cliente->cpf,
                    'telefone' => $cliente->telefone,
                    'email' => $cliente->email,
                    'dataNascimento' => $cliente->dataNascimento
                ],
                'Informacoes' => [
                    'Método de requisição' => $_SERVER['REQUEST_METHOD'],
                    'Resposta' => json_decode(Resposta::construirResp(201)->exibirResposta(), true)
                ]
            ];
            http_response_code(201); // Define o código de resposta HTTP para criação
        } else {
            $retorno = [
                'Clientes' => [],
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
                'Clientes' => [],
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
        if (isset($input['nome']) && isset($input['endereco']) && isset($input['cpf']) && isset($input['telefone']) && isset($input['email']) && isset($input['datanascimento'])) {
            $cliente = new Cliente(
                $id,
                htmlspecialchars($input['nome']),
                htmlspecialchars($input['endereco']),
                htmlspecialchars($input['cpf']),
                htmlspecialchars($input['telefone']),
                htmlspecialchars($input['email']),
                htmlspecialchars($input['datanascimento'])
            );

            $cliente->update();

            $retorno = [
                'Clientes' => [
                    'id' => $cliente->id,
                    'nome' => $cliente->nome,
                    'endereco' => $cliente->endereco,
                    'cpf' => $cliente->cpf,
                    'telefone' => $cliente->telefone,
                    'email' => $cliente->email,
                    'dataNascimento' => $cliente->dataNascimento
                ],
                'Informacoes' => [
                    'Método de requisição' => $_SERVER['REQUEST_METHOD'],
                    'Resposta' => json_decode(Resposta::construirResp(200)->exibirResposta(), true)
                ]
            ];
            http_response_code(200);
        } else {
            $retorno = [
                'Clientes' => [],
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
            $cliente = new Cliente($id);
            
            $cliente->toggleStatus();
            
            $retorno = [
                'Clientes' => [
                    'id' => $cliente->id,
                    'status' => $cliente->status
                ],
                'Informacoes' => [
                    'Método de requisição' => $_SERVER['REQUEST_METHOD'],
                    'Resposta' => json_decode(Resposta::construirResp(200)->exibirResposta(), true)
                ]
            ];
            http_response_code(200);
        } else {
            $retorno = [
                'Clientes' => [],
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
            'Clientes' => [],
            'Informacoes' => [
                'Error' => 'Método HTTP não suportado'
            ]
        ];

        echo json_encode($resposta, JSON_PRETTY_PRINT);
        break;
}
