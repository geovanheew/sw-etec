<?php
header(header: 'Content-Type: application/json');
require_once __DIR__ . '/Resposta.php';

function respostaJson($respostasPersonalizada, $codigo): bool|string {
    // Cria a resposta com base no código fornecido
    $resposta = Resposta::construirResp(codigo: $codigo);

    // Usa o operador ternário para definir o array de resposta
    $respostaArray = $respostasPersonalizada !== null && $respostasPersonalizada !== ''
        ? [
            ['resposta' => $respostasPersonalizada],
            ['codigo' => $resposta->getCodigo(), 'mensagem' => $resposta->getMensagem()]
          ]
        : [
            ['codigo' => $resposta->getCodigo(), 'mensagem' => $resposta->getMensagem()]
          ];

    // Converte o array em JSON e o retorna
    return json_encode(value: $respostaArray, flags: JSON_PRETTY_PRINT);
}

echo respostaJson(respostasPersonalizada: '', codigo: 200);