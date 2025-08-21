<?php
include_once "../Model/usuario.php";

class usuarioDAO
{
    private $conexao;

    public function __construct()
    {
        include_once '../Conexao/Conexao.php';
        $this->conexao = Conexao::getConexao();
    }

    public function cadastrarUsuario(Usuario $usuario)
    {
        $stmt = $this->conexao->prepare("INSERT INTO usuario (nome, email, login, senha) VALUES (:nome,:email, :login, :senha)");
        $stmt->bindValue(":nome", $usuario->getNome());
        $stmt->bindValue(":email", $usuario->getEmail());
        $stmt->bindValue(":login", $usuario->getLogin());
        $stmt->bindValue(":senha", $usuario->getSenha());
        
        return $stmt->execute();
    }
}