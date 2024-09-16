<?php

require_once('../Controller/DAO/Conectar.php');

class PedidosProdutos {
    public $id_pedido;
    public $id_produto;
    public $qtd;

    // Construtor da classe PedidosProdutos
    public function __construct(
        $id_pedido = null,
        $id_produto = null,
        $qtd = null
    ) {
        $this->id_pedido = $id_pedido;
        $this->id_produto = $id_produto;
        $this->qtd = $qtd;
    }

    // Método para obter um registro pelo ID do pedido
    public function get($id_pedido) {
        $mysqli = Conectar();
        $stmt = $mysqli->prepare("SELECT * FROM pedidos_produtos WHERE id_pedido = ?");
        $stmt->bind_param('i', $id_pedido);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $pedidoProduto = $resultado->fetch_all(MYSQLI_ASSOC); // Use fetch_all para retornar todos os produtos do pedido

        $stmt->close();
        $mysqli->close();

        if ($pedidoProduto) {
            return $pedidoProduto; // Retorne os dados do pedido-produto
        } else {
            return null; // Retorne null se não encontrar o pedido-produto
        }
    }
    
    public function getByFilters($id_pedido = null, $id_produto = null) {
        $mysqli = Conectar();
        $query = "SELECT * FROM pedidos_produtos WHERE 1=1";

        if ($id_pedido !== null) {
            $query .= " AND id_pedido = ?";
        }

        if ($id_produto !== null) {
            $query .= " AND id_produto = ?";
        }

        $stmt = $mysqli->prepare($query);
        $params = [];
        $types = '';

        if ($id_pedido !== null) {
            $params[] = $id_pedido;
            $types .= 'i';
        }

        if ($id_produto !== null) {
            $params[] = $id_produto;
            $types .= 'i';
        }

        if ($params) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        $resultado = $stmt->get_result();
        $pedidosProdutos = $resultado->fetch_all(MYSQLI_ASSOC);

        $stmt->close();
        $mysqli->close();

        return $pedidosProdutos;
    }

    // Método para obter todos os registros
    public function getAll() {
        $mysqli = Conectar();
        $stmt = $mysqli->prepare("SELECT * FROM pedidos_produtos");
        $stmt->execute();
        $resultado = $stmt->get_result();
        $pedidosProdutos = $resultado->fetch_all(MYSQLI_ASSOC);

        $stmt->close();
        $mysqli->close();

        return $pedidosProdutos; // Retorne todos os registros
    }

    // Método para adicionar um registro
    public function post() {
        $mysqli = Conectar();
        $stmt = $mysqli->prepare("INSERT INTO pedidos_produtos (id_pedido, id_produto, qtd) VALUES (?, ?, ?)");
        $stmt->bind_param('iii', $this->id_pedido, $this->id_produto, $this->qtd);
        $stmt->execute();

        $stmt->close();
        $mysqli->close();
    }

    // Método para atualizar um registro
    public function put() {
        $mysqli = Conectar();
        $stmt = $mysqli->prepare("UPDATE pedidos_produtos SET qtd = ? WHERE id_pedido = ? AND id_produto = ?");
        $stmt->bind_param('iii', $this->qtd, $this->id_pedido, $this->id_produto);
        $stmt->execute();

        $stmt->close();
        $mysqli->close();
    }

    // Método para deletar um registro
    public function delete($id_pedido) {
        $mysqli = Conectar();
        $stmt = $mysqli->prepare("DELETE FROM pedidos_produtos WHERE id_pedido = ?");
        $stmt->bind_param('i', $id_pedido);
        $stmt->execute();

        $stmt->close();
        $mysqli->close();
    }
}
