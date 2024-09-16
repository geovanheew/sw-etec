<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

require_once '../Model/PedidosProdutosModel.php';
require_once '../Model/Utilidades/Resposta.php';

// Início da sessão

switch ($_SERVER['REQUEST_METHOD']) {
    case "GET":
        $pedidosProdutos = new PedidosProdutos();
        
        $id_pedido = isset($_GET['id_pedido']) ? htmlspecialchars($_GET['id_pedido']) : null;
        $id_produto = isset($_GET['id_produto']) ? htmlspecialchars($_GET['id_produto']) : null;

        $pedidoProdutoData = $pedidosProdutos->getByFilters($id_pedido, $id_produto);

        if ($pedidoProdutoData) {
            $retorno = [
                'PedidosProdutos' => $pedidoProdutoData,
                'Informacoes' => [
                    'Método de requisição' => $_SERVER['REQUEST_METHOD'],
                    'Resposta' => json_decode(Resposta::construirResp(200)->exibirResposta(), true)
                ]
            ];
        } else {
            $retorno = [
                'PedidosProdutos' => [],
                'Informacoes' => [
                    'Método de requisição' => $_SERVER['REQUEST_METHOD'],
                    'Resposta' => json_decode(Resposta::construirResp(404)->exibirResposta(), true)
                ]
            ];
        }
        echo json_encode($retorno, JSON_PRETTY_PRINT);
        break;

    case "POST":
        break;

    case "PUT":
        break;

    case "DELETE":
        break;

    default:
        $resposta = [
            'PedidosProdutos' => [],
            'Informacoes' => [
                'Error' => 'Método HTTP não suportado'
            ]
        ];

        echo json_encode($resposta, JSON_PRETTY_PRINT);
        break;
}
