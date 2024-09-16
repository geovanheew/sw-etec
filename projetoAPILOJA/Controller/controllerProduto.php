<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

require_once '../Model/ProdutoModel.php';
require_once '../Model/Utilidades/Resposta.php';

// Início da sessão

switch ($_SERVER['REQUEST_METHOD']) {
    case "GET":
        $produto = new Produto();
        if (isset($_GET['id'])) {
            $id = htmlspecialchars($_GET['id']);
            $produtoData = $produto->get($id); // Presumindo que `get` retorna dados do produto como um array

            if ($produtoData) {
                $retorno = [
                    'Produtos' => [$produtoData], // Coloca o produto em um array para manter a consistência
                    'Informacoes' => [
                        'Método de requisição' => $_SERVER['REQUEST_METHOD'],
                        'Resposta' => json_decode(Resposta::construirResp(200)->exibirResposta(), true) // Código 200 para OK
                    ]
                ];
            } else {
                $retorno = [
                    'Produtos' => [],
                    'Informacoes' => [
                        'Método de requisição' => $_SERVER['REQUEST_METHOD'],
                        'Resposta' => json_decode(Resposta::construirResp(404)->exibirResposta(), true) // Código 404 para não encontrado
                    ]
                ];
            }
        } else {
            $produtosData = $produto->getAll(); // Obtém todos os produtos

            if (!empty($produtosData)) {
                $retorno = [
                    'Produtos' => $produtosData, // Coloca todos os produtos em um array
                    'Informacoes' => [
                        'Método de requisição' => $_SERVER['REQUEST_METHOD'],
                        'Resposta' => json_decode(Resposta::construirResp(200)->exibirResposta(), true) // Código 200 para OK
                    ]
                ];
            } else {
                $retorno = [
                    'Produtos' => [],
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
            'Produtos' => [],
            'Informacoes' => [
                'Error' => 'Método HTTP não suportado'
            ]
        ];

        echo json_encode($resposta, JSON_PRETTY_PRINT);
        break;
}
