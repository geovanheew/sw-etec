<?php

require_once ('../Controller/DAO/Conectar.php');

class Product {
    public $id;
    public $nome;
    public $descricao;
    public $qtd;
    public $marca;
    public $preco;
    public $validade;
    public $status;

    // Construtor
    public function __construct(
        $id_informado = null,
        $nome_informado = null,
        $descricao_informada = null,
        $qtd_informada = null,
        $marca_informada = null,
        $preco_informado = null,
        $validade_informada = null,
        $status_informado = null
    ) {
        $this->id = $id_informado;
        $this->nome = $nome_informado;
        $this->descricao = $descricao_informada;
        $this->qtd = $qtd_informada;
        $this->marca = $marca_informada;
        $this->preco = $preco_informado;
        $this->validade = $validade_informada;
        $this->status = $status_informado;
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
    
        return $produtos; // Retorne todos os dados dos produtos
    }
    
    // Método para adicionar um produto
    public function post() {
        $mysqli = Conectar();
        $stmt = $mysqli->prepare("INSERT INTO Produtos (Nome, Descricao, QTD, Marca, Preco, Validade) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('ssisss', $this->nome, $this->descricao, $this->qtd, $this->marca, $this->preco, $this->validade);
        $stmt->execute();
        $this->id = $mysqli->insert_id; // Define o ID gerado após o INSERT

        $stmt->close();
        $mysqli->close();
    }

    public function update() {
        $mysqli = Conectar();
    
        $stmt = $mysqli->prepare("UPDATE Produtos SET Nome = ?, Descricao = ?, QTD = ?, Marca = ?, Preco = ?, Validade = ? WHERE ID = ?");
        $stmt->bind_param('ssisssi', $this->nome, $this->descricao, $this->qtd, $this->marca, $this->preco, $this->validade, $this->id);
        $stmt->execute();
    
        $stmt->close();
        $mysqli->close();
    }
    
    public function toggleStatus() {
        $mysqli = Conectar();
        
        // Primeiro, obtenha o status atual do produto
        $stmt = $mysqli->prepare("SELECT status FROM Produtos WHERE ID = ?");
        $stmt->bind_param('i', $this->id);
        $stmt->execute();
        $result = $stmt->get_result();
        $produto = $result->fetch_assoc();
        
        if ($produto) {
            // Alterna o status baseado no valor atual
            $newStatus = ($produto['status'] == 0) ? 1 : 0;
            
            // Atualiza o status
            $stmt = $mysqli->prepare("UPDATE Produtos SET status = ? WHERE ID = ?");
            $stmt->bind_param('ii', $newStatus, $this->id);
            $stmt->execute();
            
            // Recarrega os dados do produto após a atualização
            $stmt = $mysqli->prepare("SELECT * FROM Produtos WHERE ID = ?");
            $stmt->bind_param('i', $this->id);
            $stmt->execute();
            $result = $stmt->get_result();
            $produto = $result->fetch_assoc();
            
            // Atualiza as propriedades do objeto Product
            $this->status = $produto['status'];
        }
        
        $stmt->close();
        $mysqli->close();
    }
}
