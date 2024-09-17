<?php

require_once ('../Controller/DAO/Conectar.php');

class Cliente {
    public $id;
    public $nome;
    public $endereco;
    public $cpf;
    public $telefone;
    public $email;
    public $dataNascimento;
    public $status;

    // Construtor
    public function __construct(
        $id_informado = null,
        $nome_informado = null,
        $endereco_informado = null,
        $cpf_informado = null,
        $telefone_informado = null,
        $email_informado = null,
        $dataNascimento_informada = null,
        $status_informado = null
    ) {
        $this->id = $id_informado;
        $this->nome = $nome_informado;
        $this->endereco = $endereco_informado;
        $this->cpf = $cpf_informado;
        $this->telefone = $telefone_informado;
        $this->email = $email_informado;
        $this->dataNascimento = $dataNascimento_informada;
        $this->status = $status_informado;
    }

    // Método para obter um cliente pelo ID
    public function get($id) {
        $mysqli = Conectar();
        $stmt = $mysqli->prepare("SELECT * FROM Clientes WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $cliente = $resultado->fetch_assoc();
    
        $stmt->close();
        $mysqli->close();
    
        if ($cliente) {
            return $cliente; // Retorne os dados do cliente
        } else {
            return null; // Retorne null se não encontrar o cliente
        }
    }

    // Método para obter todos os clientes
    public function getAll() {
        $mysqli = Conectar();
        $stmt = $mysqli->prepare("SELECT * FROM Clientes");
        $stmt->execute();
        $resultado = $stmt->get_result();
        $clientes = $resultado->fetch_all(MYSQLI_ASSOC);
    
        $stmt->close();
        $mysqli->close();
    
        return $clientes; // Retorne todos os dados dos clientes
    }
    
    // Método para adicionar um cliente
    public function post() {

        $dataNascimentoFormatada = date('Y-m-d', strtotime(str_replace('/', '-', $this->dataNascimento)));

        $mysqli = Conectar();
        $stmt = $mysqli->prepare("INSERT INTO Clientes (Nome, Endereco, CPF, Telefone, Email, DataNascimento) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('ssssss', $this->nome, $this->endereco, $this->cpf, $this->telefone, $this->email, $dataNascimentoFormatada);
        $stmt->execute();
        $this->id = $mysqli->insert_id; // Define o ID gerado após o INSERT

        $stmt->close();
        $mysqli->close();
        }

        public function update() {
            $mysqli = Conectar();
        
            // Formata a data
            $date = DateTime::createFromFormat('d/m/Y', $this->dataNascimento);
            if ($date) {
                $formattedDate = $date->format('Y-m-d');
            } else {
                $formattedDate = null;
            }
        
            $stmt = $mysqli->prepare("UPDATE Clientes SET Nome = ?, Endereco = ?, CPF = ?, Telefone = ?, Email = ?, DataNascimento = ? WHERE ID = ?");
            $stmt->bind_param('ssssssi', $this->nome, $this->endereco, $this->cpf, $this->telefone, $this->email, $formattedDate, $this->id);
            $stmt->execute();
        
            $stmt->close();
            $mysqli->close();
        }
        
        public function toggleStatus() {
            $mysqli = Conectar();
            
            // Primeiro, obtenha o status atual do cliente
            $stmt = $mysqli->prepare("SELECT status FROM Clientes WHERE ID = ?");
            $stmt->bind_param('i', $this->id);
            $stmt->execute();
            $result = $stmt->get_result();
            $cliente = $result->fetch_assoc();
            
            if ($cliente) {
                // Alterna o status baseado no valor atual
                $newStatus = ($cliente['status'] == 0) ? 1 : 0;
                
                // Atualiza o status
                $stmt = $mysqli->prepare("UPDATE Clientes SET status = ? WHERE ID = ?");
                $stmt->bind_param('ii', $newStatus, $this->id);
                $stmt->execute();
                
                // Recarrega os dados do cliente após a atualização
                $stmt = $mysqli->prepare("SELECT * FROM Clientes WHERE ID = ?");
                $stmt->bind_param('i', $this->id);
                $stmt->execute();
                $result = $stmt->get_result();
                $cliente = $result->fetch_assoc();
                
                // Atualiza as propriedades do objeto Cliente
                $this->status = $cliente['status'];
            }
            
            $stmt->close();
            $mysqli->close();
        }
        
        
        
}