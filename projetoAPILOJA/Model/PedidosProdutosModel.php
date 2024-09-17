<?php

require_once ('../Controller/DAO/Conectar.php');

class PedidoProduto {
    public $id_pedido;
    public $id_produto;
    public $qtd;
    public $status;

    // Construtor
    public function __construct(
        $id_pedido = null,
        $id_produto = null,
        $qtd = null,
        $status = null
    ) {
        $this->id_pedido = $id_pedido;
        $this->id_produto = $id_produto;
        $this->qtd = $qtd;
        $this->status = $status;
    }

    // Método para obter um pedido produto pelo ID
    public function get($id_pedido, $id_produto) {
        $mysqli = Conectar();
        $stmt = $mysqli->prepare("SELECT * FROM Pedidos_Produtos WHERE id_pedido = ? AND id_produto = ?");
        $stmt->bind_param('ii', $id_pedido, $id_produto);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $pedidoProduto = $resultado->fetch_assoc();
    
        $stmt->close();
        $mysqli->close();
    
        return $pedidoProduto ? $pedidoProduto : null;
    }

    // Método para obter todos os pedidos produtos
    public function getAll() {
        $mysqli = Conectar();
        $stmt = $mysqli->prepare("SELECT * FROM Pedidos_Produtos");
        $stmt->execute();
        $resultado = $stmt->get_result();
        $pedidosProdutos = $resultado->fetch_all(MYSQLI_ASSOC);
    
        $stmt->close();
        $mysqli->close();
    
        return $pedidosProdutos;
    }

    public function getByPedido($id_pedido) {
        $mysqli = Conectar();
        $stmt = $mysqli->prepare("SELECT * FROM Pedidos_Produtos WHERE id_pedido = ?");
        $stmt->bind_param('i', $id_pedido);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $pedidosProdutos = $resultado->fetch_all(MYSQLI_ASSOC);
        
        $stmt->close();
        $mysqli->close();
        
        return $pedidosProdutos;
    }
    
    public function getByProduto($id_produto) {
        $mysqli = Conectar();
        $stmt = $mysqli->prepare("SELECT * FROM Pedidos_Produtos WHERE id_produto = ?");
        $stmt->bind_param('i', $id_produto);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $pedidosProdutos = $resultado->fetch_all(MYSQLI_ASSOC);
        
        $stmt->close();
        $mysqli->close();
        
        return $pedidosProdutos;
    }
    
    
    // Método para adicionar um pedido produto
// Método para adicionar um pedido produto
// Método para adicionar um pedido produto
public function post() {
    $mysqli = Conectar();
    
    // Inserir o pedido produto
    $stmt = $mysqli->prepare("INSERT INTO Pedidos_Produtos (id_pedido, id_produto, qtd) VALUES (?, ?, ?)");
    $stmt->bind_param('iii', $this->id_pedido, $this->id_produto, $this->qtd);
    $stmt->execute();
    
    // Captura o ID do pedido produto recém-inserido
    $id_pedido_produto = $mysqli->insert_id;

    // Recupera o status do novo registro
    $stmt = $mysqli->prepare("SELECT status FROM Pedidos_Produtos WHERE id_pedido = ? AND id_produto = ?");
    $stmt->bind_param('ii', $this->id_pedido, $this->id_produto);
    $stmt->execute();
    $result = $stmt->get_result();
    $pedidoProduto = $result->fetch_assoc();
    
    // Atualiza as propriedades do objeto
    $this->status = $pedidoProduto['status'];
    
    $stmt->close();
    $mysqli->close();
}


    // Método para atualizar um pedido produto
    public function update() {
        $mysqli = Conectar();
        $stmt = $mysqli->prepare("UPDATE Pedidos_Produtos SET qtd = ?, status = ? WHERE id_pedido = ? AND id_produto = ?");
        $stmt->bind_param('isis', $this->qtd, $this->status, $this->id_pedido, $this->id_produto);
        $stmt->execute();
        
        $stmt->close();
        $mysqli->close();
    }

    // Método para alternar o status (similar ao Cliente)
    public function toggleStatus() {
        $mysqli = Conectar();
        
        // Primeiro, obtenha o status atual
        $stmt = $mysqli->prepare("SELECT status FROM Pedidos_Produtos WHERE id_pedido = ? AND id_produto = ?");
        $stmt->bind_param('ii', $this->id_pedido, $this->id_produto);
        $stmt->execute();
        $result = $stmt->get_result();
        $pedidoProduto = $result->fetch_assoc();
        
        if ($pedidoProduto) {
            // Alterna o status baseado no valor atual
            $newStatus = ($pedidoProduto['status'] == 0) ? 1 : 0;
            
            // Atualiza o status
            $stmt = $mysqli->prepare("UPDATE Pedidos_Produtos SET status = ? WHERE id_pedido = ? AND id_produto = ?");
            $stmt->bind_param('iii', $newStatus, $this->id_pedido, $this->id_produto);
            $stmt->execute();
            
            // Recarrega os dados após a atualização
            $stmt = $mysqli->prepare("SELECT * FROM Pedidos_Produtos WHERE id_pedido = ? AND id_produto = ?");
            $stmt->bind_param('ii', $this->id_pedido, $this->id_produto);
            $stmt->execute();
            $result = $stmt->get_result();
            $pedidoProduto = $result->fetch_assoc();
            
            // Atualiza as propriedades do objeto
            $this->status = $pedidoProduto['status'];
        }
        
        $stmt->close();
        $mysqli->close();
    }

    // Método para atualizar somente a quantidade de um pedido produto
    public function updateQuantity() {
        $mysqli = Conectar();
        $stmt = $mysqli->prepare("UPDATE Pedidos_Produtos SET qtd = ? WHERE id_pedido = ? AND id_produto = ?");
        $stmt->bind_param('iii', $this->qtd, $this->id_pedido, $this->id_produto);
        $stmt->execute();
        
        $stmt->close();
        $mysqli->close();
    }

}
