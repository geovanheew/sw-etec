<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

require_once '../Model/PedidoModel.php';
require_once '../Model/Utilidades/Resposta.php';

// Início da sessão

switch ($_SERVER['REQUEST_METHOD']) {
    case "GET":
        $pedido = new Pedido();
        if (isset($_GET['id'])) {
            $id = htmlspecialchars($_GET['id']);
            $pedidoData = $pedido->get($id); // Presumindo que `get` retorna dados do pedido como um array

            if ($pedidoData) {
                $retorno = [
                    'Pedidos' => [$pedidoData], // Coloca o pedido em um array para manter a consistência
                    'Informacoes' => [
                        'Método de requisição' => $_SERVER['REQUEST_METHOD'],
                        'Resposta' => json_decode(Resposta::construirResp(200)->exibirResposta(), true) // Usa json_decode para obter um array associativo
                    ]
                ];
            } else {
                $retorno = [
                    'Pedidos' => [],
                    'Informacoes' => [
                        'Método de requisição' => $_SERVER['REQUEST_METHOD'],
                        'Resposta' => json_decode(Resposta::construirResp(404)->exibirResposta(), true) // Código 404 para não encontrado
                    ]
                ];
            }
        } else {
            // Se não houver ID, você pode implementar a lógica para obter todos os pedidos, se necessário.
            // Suponha que você tenha um método getAll() em Pedido que retorna todos os pedidos.
            $pedidosData = $pedido->getAll(); // Obtém todos os pedidos

            if (!empty($pedidosData)) {
                $retorno = [
                    'Pedidos' => $pedidosData, // Coloca todos os pedidos em um array
                    'Informacoes' => [
                        'Método de requisição' => $_SERVER['REQUEST_METHOD'],
                        'Resposta' => json_decode(Resposta::construirResp(200)->exibirResposta(), true) // Código 200 para OK
                    ]
                ];
            } else {
                $retorno = [
                    'Pedidos' => [],
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
