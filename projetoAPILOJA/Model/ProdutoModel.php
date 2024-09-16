<?php

require_once('../Controller/DAO/Conectar.php');

class Produto {
    public $id;
    public $nome;
    public $descricao;
    public $qtd;
    public $marca;
    public $preco;
    public $validade;
    public $status;

    // Construtor da classe Produto
    public function __construct(
        $id = null,
        $nome = null,
        $descricao = null,
        $qtd = null,
        $marca = null,
        $preco = null,
        $validade = null,
        $status = null
    ) {
        $this->id = $id;
        $this->nome = $nome;
        $this->descricao = $descricao;
        $this->qtd = $qtd;
        $this->marca = $marca;
        $this->preco = $preco;
        $this->validade = $validade;
        $this->status = $status;
    }

    // Método para obter um produto pelo ID
    public function get($id) {
        $mysqli = Conectar();
        $stmt = $mysqli->prepare("SELECT * FROM Produtos WHERE ID = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $produto = $resultado->fetch_assoc();

        $stmt->close();
        $mysqli->close();

        if ($produto) {
            return $produto; // Retorne os dados do produto
        } else {
            return null; // Retorne null se não encontrar o produto
        }
    }

    // Método para obter todos os produtos
    public function getAll() {
        $mysqli = Conectar();
        $stmt = $mysqli->prepare("SELECT * FROM Produtos");
        $stmt->execute();
        $resultado = $stmt->get_result();
        $produtos = $resultado->fetch_all(MYSQLI_ASSOC);

        $stmt->close();
        $mysqli->close();

        return $produtos; // Retorne todos os produtos
    }

    // Método para adicionar um produto
    public function post() {
        $mysqli = Conectar();
        $stmt = $mysqli->prepare("INSERT INTO Produtos (nome, descricao, qtd, marca, preco, validade, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('ssissss', $this->nome, $this->descricao, $this->qtd, $this->marca, $this->preco, $this->validade, $this->status);
        $stmt->execute();
        $this->id = $mysqli->insert_id; // Define o ID gerado após o INSERT

        $stmt->close();
        $mysqli->close();
    }

    // Método para atualizar um produto
    public function put() {
        $mysqli = Conectar();
        $stmt = $mysqli->prepare("UPDATE Produtos SET nome = ?, descricao = ?, qtd = ?, marca = ?, preco = ?, validade = ?, status = ? WHERE ID = ?");
        $stmt->bind_param('ssissssi', $this->nome, $this->descricao, $this->qtd, $this->marca, $this->preco, $this->validade, $this->status, $this->id);
        $stmt->execute();

        $stmt->close();
        $mysqli->close();
    }

    // Método para deletar um produto
    public function delete($id) {
        $mysqli = Conectar();
        $stmt = $mysqli->prepare("DELETE FROM Produtos WHERE ID = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();

        $stmt->close();
        $mysqli->close();
    }
}
?>
