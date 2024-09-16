<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

require_once '../Model/ClienteModel.php';
// Inicio da sessão

switch ($_SERVER['REQUEST_METHOD']) {
    case "GET":
        $obj = new Cliente();
        $obj->get('1');
        
        break;
    case "POST":

        break;
    case "PUT":

        break;
    case "PATCH":

        break;
    case "DELETE":

        break;
    default:
        $resposta = [
            'Error:' => $_SERVER['REQUEST_METHOD']   
        ];

        echo json_encode($resposta, JSON_PRETTY_PRINT);
        break;
}