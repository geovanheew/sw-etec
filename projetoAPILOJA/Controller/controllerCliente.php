<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

require_once '../Model/ClienteModel.php';
require_once '../Model/Utilidades/Resposta.php';

// Início da sessão

switch ($_SERVER['REQUEST_METHOD']) {
    case "GET":
        $cliente = new Cliente();
        if (isset($_GET['id'])) {
            $id = htmlspecialchars($_GET['id']);
            $clienteData = $cliente->get($id); // Presumindo que `get` retorna dados do cliente como um array

            if ($clienteData) {
                $retorno = [
                    'Clientes' => [$clienteData], // Coloca o cliente em um array para manter a consistência
                    'Informacoes' => [
                        'Método de requisição' => $_SERVER['REQUEST_METHOD'],
                        'Resposta' => json_decode(Resposta::construirResp(200)->exibirResposta(), true) // Usa json_decode para obter um array associativo
                    ]
                ];
            } else {
                $retorno = [
                    'Clientes' => [],
                    'Informacoes' => [
                        'Método de requisição' => $_SERVER['REQUEST_METHOD'],
                        'Resposta' => json_decode(Resposta::construirResp(404)->exibirResposta(), true) // Código 404 para não encontrado
                    ]
                ];
            }
        } else {
            $clientesData = $cliente->getAll(); // Obtém todos os clientes

            if (!empty($clientesData)) {
                $retorno = [
                    'Clientes' => $clientesData, // Coloca todos os clientes em um array
                    'Informacoes' => [
                        'Método de requisição' => $_SERVER['REQUEST_METHOD'],
                        'Resposta' => json_decode(Resposta::construirResp(200)->exibirResposta(), true) // Código 200 para OK
                    ]
                ];
            } else {
                $retorno = [
                    'Clientes' => [],
                    'Informacoes' => [
                        'Método de requisição' => $_SERVER['REQUEST_METHOD'],
                        'Resposta' => json_decode(Resposta::construirResp(404)->exibirResposta(), true) // Código 404 para não encontrado
                    ]
                ];
            }
        }
        echo json_encode($retorno, JSON_PRETTY_PRINT);
        break;

    case "POST":
        // Implementar POST aqui
        break;

    case "PUT":
        // Implementar PUT aqui
        break;

    case "PATCH":
        // Implementar PATCH aqui
        break;

    case "DELETE":
        // Implementar DELETE aqui
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
