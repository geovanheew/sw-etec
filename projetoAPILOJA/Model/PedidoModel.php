<?php

require_once('../Controller/DAO/Conectar.php');

class Pedido {
    public $id_pedido;
    public $id_cliente;
    public $data;
    public $status; // Presumindo que a coluna status é opcional e pode ser usada em outros contextos

    // Construtor
    public function __construct(
        $id_pedido_informado = null,
        $id_cliente_informado = null,
        $data_informada = null,
        $status_informado = null
    ) {
        $this->id_pedido = $id_pedido_informado;
        $this->id_cliente = $id_cliente_informado;
        $this->data = $data_informada;
        $this->status = $status_informado; // Manter a propriedade status mesmo que não esteja na tabela
    }

    // Método para obter um pedido pelo ID
    public function get($id_pedido) {
        $mysqli = Conectar();
        $stmt = $mysqli->prepare("SELECT * FROM Pedidos WHERE id_pedido = ?");
        $stmt->bind_param('i', $id_pedido);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $pedido = $resultado->fetch_assoc();
    
        $stmt->close();
        $mysqli->close();
    
        if ($pedido) {
            return $pedido; // Retorne os dados do pedido
        } else {
            return null; // Retorne null se não encontrar o pedido
        }
    }

    // Método para obter todos os pedidos
    public function getAll() {
        $mysqli = Conectar();
        $stmt = $mysqli->prepare("SELECT * FROM Pedidos");
        $stmt->execute();
        $resultado = $stmt->get_result();
        $pedidos = $resultado->fetch_all(MYSQLI_ASSOC);
    
        $stmt->close();
        $mysqli->close();
    
        return $pedidos; // Retorne todos os dados dos pedidos
    }
    
    // Método para adicionar um pedido
    public function post() {
        $mysqli = Conectar();
        $stmt = $mysqli->prepare("INSERT INTO Pedidos (id_cliente, data) VALUES (?, ?)");
        $stmt->bind_param('is', $this->id_cliente, $this->data);
        $stmt->execute();
        $this->id_pedido = $mysqli->insert_id; // Define o ID gerado após o INSERT

        $stmt->close();
        $mysqli->close();
    }

    public function update() {
        $mysqli = Conectar();
        
        $stmt = $mysqli->prepare("UPDATE Pedidos SET id_cliente = ?, data = ? WHERE id_pedido = ?");
        $stmt->bind_param('isi', $this->id_cliente, $this->data, $this->id_pedido);
        $stmt->execute();
        
        $stmt->close();
        $mysqli->close();
    }
    
    public function toggleStatus() {
        $mysqli = Conectar();
        
        // Primeiro, obtenha o status atual do pedido
        $stmt = $mysqli->prepare("SELECT status FROM Pedidos WHERE id_pedido = ?");
        $stmt->bind_param('i', $this->id_pedido);
        $stmt->execute();
        $result = $stmt->get_result();
        $pedido = $result->fetch_assoc();
        
        if ($pedido) {
            // Alterna o status baseado no valor atual
            $newStatus = ($pedido['status'] == 0) ? 1 : 0;
            
            // Atualiza o status
            $stmt = $mysqli->prepare("UPDATE Pedidos SET status = ? WHERE id_pedido = ?");
            $stmt->bind_param('ii', $newStatus, $this->id_pedido);
            $stmt->execute();
            
            // Recarrega os dados do pedido após a atualização
            $stmt = $mysqli->prepare("SELECT * FROM Pedidos WHERE id_pedido = ?");
            $stmt->bind_param('i', $this->id_pedido);
            $stmt->execute();
            $result = $stmt->get_result();
            $pedido = $result->fetch_assoc();
            
            // Atualiza as propriedades do objeto Pedido
            $this->status = $pedido['status'];
        }
        
        $stmt->close();
        $mysqli->close();
    }
}
