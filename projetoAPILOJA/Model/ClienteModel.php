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

    // Construtor
    public function __construct(
        $id_informado = null,
        $nome_informado = null,
        $endereco_informado = null,
        $cpf_informado = null,
        $telefone_informado = null,
        $email_informado = null,
        $dataNascimento_informada = null
    ) {
        $this->id = $id_informado;
        $this->nome = $nome_informado;
        $this->endereco = $endereco_informado;
        $this->cpf = $cpf_informado;
        $this->telefone = $telefone_informado;
        $this->email = $email_informado;
        $this->dataNascimento = $dataNascimento_informada;
    }


    // Método para obter um cliente pelo ID
    public function get($id) {
        $mysqli = Conectar();
        $stmt = $mysqli->prepare("SELECT * FROM Clientes WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $cliente = $resultado->fetch_assoc();

        if ($cliente) {
            $this->id = $cliente['ID'];
            $this->nome = $cliente['Nome'];
            $this->endereco = $cliente['Endereco'];
            $this->cpf = $cliente['CPF'];
            $this->telefone = $cliente['Telefone'];
            $this->email = $cliente['Email'];
            $this->dataNascimento = $cliente['DataNascimento'];
        }

        $stmt->close();
        $mysqli->close();
    }

    // Método para adicionar um cliente
    public function post() {
        $mysqli = Conectar();
        $stmt = $mysqli->prepare("INSERT INTO cliente (nome, endereco, cpf, telefone, email, data_nascimento) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('ssssss', $this->nome, $this->endereco, $this->cpf, $this->telefone, $this->email, $this->dataNascimento);
        $stmt->execute();
        $this->id = $mysqli->insert_id; // Define o ID gerado após o INSERT

        $stmt->close();
        $mysqli->close();
    }
}
