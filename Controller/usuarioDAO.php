<?php
include_once "../Model/Usuario.php";

class UsuarioDAO
{
    private $conexao;

    public function __construct()
    {
        include_once '../Conexao/Conexao.php';
        $this->conexao = Conexao::getConexao();
    }

    // 🔹 Cadastrar novo usuário
    public function cadastrarUsuario(Usuario $usuario)
    {
        $stmt = $this->conexao->prepare("
            INSERT INTO usuario (nome, email, setor, login, senha)
            VALUES (:nome, :email, :setor, :login, :senha)
        ");
        $stmt->bindValue(":nome", $usuario->getNome());
        $stmt->bindValue(":email", $usuario->getEmail());
        $stmt->bindValue(":setor", $usuario->getSetor());
        $stmt->bindValue(":login", $usuario->getLogin());
        $stmt->bindValue(":senha", $usuario->getSenha());
        
        return $stmt->execute();
    }

    // 🔹 Buscar usuário por ID
    public function buscarPorId($id)
    {
        $sql = "SELECT id, nome, email, setor, login FROM usuario WHERE id = :id";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 🔹 Listar todos os usuários
    public function listarTodos()
    {
        $sql = "SELECT id, nome, email, setor, login FROM usuario ORDER BY nome ASC";
        $stmt = $this->conexao->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
