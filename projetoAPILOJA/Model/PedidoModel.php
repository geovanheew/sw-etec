<?php

require_once('../Controller/DAO/Conectar.php');

class Pedido {
    public $id;
    public $cliente_id;
    public $data_pedido;
    public $status_pedido;

    // Construtor da classe Pedido
    public function __construct(
        $id = null,
        $cliente_id = null,
        $data_pedido = null,
        $status_pedido = null
    ) {
        $this->id = $id;
        $this->cliente_id = $cliente_id;
        $this->data_pedido = $data_pedido;
        $this->status_pedido = $status_pedido;
    }

    // Método para obter um pedido pelo ID
    public function get($id) {
        $mysqli = Conectar();
        $stmt = $mysqli->prepare("SELECT * FROM Pedidos WHERE id_pedido = ?");
        $stmt->bind_param('i', $id);
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

        return $pedidos; // Retorne todos os pedidos
    }

    // Método para adicionar um pedido
    public function post() {
        $mysqli = Conectar();
        $stmt = $mysqli->prepare("INSERT INTO pedidos (cliente_id, data_pedido, status_pedido) VALUES (?, ?, ?)");
        $stmt->bind_param('iss', $this->cliente_id, $this->data_pedido, $this->status_pedido);
        $stmt->execute();
        $this->id = $mysqli->insert_id; // Define o ID gerado após o INSERT

        $stmt->close();
        $mysqli->close();
    }

    // Método para atualizar um pedido
    public function put() {
        $mysqli = Conectar();
        $stmt = $mysqli->prepare("UPDATE pedidos SET cliente_id = ?, data_pedido = ?, status_pedido = ? WHERE id_pedido = ?");
        $stmt->bind_param('issi', $this->cliente_id, $this->data_pedido, $this->status_pedido, $this->id);
        $stmt->execute();

        $stmt->close();
        $mysqli->close();
    }

    // Método para deletar um pedido
    public function delete($id) {
        $mysqli = Conectar();
        $stmt = $mysqli->prepare("DELETE FROM pedidos WHERE id_pedido = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();

        $stmt->close();
        $mysqli->close();
    }
}
