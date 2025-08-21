<?php

include_once "../Model/Registro.php";

class RegistroDAO
{
    private $conexao;

     public function __construct()
    {
        include_once '../Conexao/Conexao.php';
        $this->conexao = Conexao::getConexao();
    }

    public function cadastrarRegistro
}