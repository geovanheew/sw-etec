<?php
class Resposta {
    private $codigo;
    private $mensagem;

    // Construtor da classe
    private function __construct($codigo, $mensagem) {
        $this->codigo = $codigo;
        $this->mensagem = $mensagem;
    }

    // Método estático para construir uma instância de Resposta
    public static function construirResp($codigo) {
        switch ($codigo) {
            case '200':
                $mensagem = 'Sucesso!';
                break;
            case '201':
                $mensagem = 'Criado!';
                break;
            case '204':
                $mensagem = 'Sem conteúdo, sucesso!';
                break;
            // Códigos de erro
            case '400':
                $mensagem = 'Erro no pedido';
                break;
            case '401':
                $mensagem = 'Não autorizado';
                break;
            case '403':
                $mensagem = 'Proibido';
                break;
            case '404':
                $mensagem = 'Não encontrado';
                break;
            case '405':
                $mensagem = 'Método não permitido';
                break;
            case '409':
                $mensagem = 'Conflito';
                break;
            // Código inválido
            default:
                $mensagem = 'ERRO NA MENSAGEM, CÓDIGO INSERIDO INVÁLIDO';
                break;
        }
        return new self($codigo, $mensagem);
    }

    public function getCodigo() {
        return $this->codigo;
    }

    public function getMensagem() {
        return $this->mensagem;
    }

    // Método para exibir a resposta em formato JSON
    public function exibirResposta() {
        return json_encode([
            'codigo' => $this->codigo,
            'mensagem' => $this->mensagem
        ]);
    }
}