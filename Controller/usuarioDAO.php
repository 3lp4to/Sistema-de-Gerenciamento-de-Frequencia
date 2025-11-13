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

    /**
     * 🔹 Cadastrar novo usuário (sem criptografia)
     */
    public function cadastrarUsuario(Usuario $usuario)
    {
        try {
            $stmt = $this->conexao->prepare("
                INSERT INTO usuario (nome, email, setor, login, senha, idsupervisor)
                VALUES (:nome, :email, :setor, :login, :senha, :idsupervisor)
            ");

            $stmt->bindValue(":nome", $usuario->getNome());
            $stmt->bindValue(":email", $usuario->getEmail());
            $stmt->bindValue(":setor", $usuario->getSetor());
            $stmt->bindValue(":login", $usuario->getLogin());
            $stmt->bindValue(":senha", $usuario->getSenha()); // senha em texto puro
            $stmt->bindValue(":idsupervisor", $usuario->getIdSupervisor(), PDO::PARAM_INT);

            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Erro ao cadastrar o usuário: " . $e->getMessage();
            return false;
        }
    }

    /**
     * 🔹 Buscar usuário por ID
     */
    public function buscarPorId($id)
    {
        try {
            $sql = "SELECT idusuario, nome, email, setor, login, idsupervisor 
                    FROM usuario 
                    WHERE idusuario = :id";

            $stmt = $this->conexao->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Erro ao buscar o usuário: " . $e->getMessage();
            return null;
        }
    }

    /**
     * 🔹 Listar todos os usuários
     */
    public function listarTodos()
    {
        try {
            $sql = "SELECT idusuario, nome, email, setor, login, idsupervisor 
                    FROM usuario 
                    ORDER BY nome ASC";

            $stmt = $this->conexao->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Erro ao listar usuários: " . $e->getMessage();
            return [];
        }
    }

    /**
     * 🔹 Buscar usuário por login (para login do sistema)
     */
    public function buscarPorLogin($login)
    {
        try {
            $sql = "SELECT * FROM usuario WHERE login = :login";
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindValue(':login', $login);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Erro ao buscar o login: " . $e->getMessage();
            return null;
        }
    }

    /**
     * 🔹 Atualizar dados do usuário (sem criptografia)
     */
    public function atualizarUsuario(Usuario $usuario)
    {
        try {
            $sql = "UPDATE usuario 
                    SET nome = :nome,
                        email = :email,
                        setor = :setor,
                        login = :login,
                        senha = :senha,
                        idsupervisor = :idsupervisor
                    WHERE idusuario = :id";

            $stmt = $this->conexao->prepare($sql);
            $stmt->bindValue(":nome", $usuario->getNome());
            $stmt->bindValue(":email", $usuario->getEmail());
            $stmt->bindValue(":setor", $usuario->getSetor());
            $stmt->bindValue(":login", $usuario->getLogin());
            $stmt->bindValue(":senha", $usuario->getSenha()); // senha em texto puro
            $stmt->bindValue(":idsupervisor", $usuario->getIdSupervisor(), PDO::PARAM_INT);
            $stmt->bindValue(":id", $usuario->getId(), PDO::PARAM_INT);

            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Erro ao atualizar o usuário: " . $e->getMessage();
            return false;
        }
    }

    /**
     * 🔹 Excluir usuário
     */
    public function excluirUsuario($id)
    {
        try {
            $sql = "DELETE FROM usuario WHERE idusuario = :id";
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);

            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Erro ao excluir o usuário: " . $e->getMessage();
            return false;
        }
    }

    /**
     * 🔹 Listar subordinados de um supervisor
     */
    public function listarPorSupervisor($idSupervisor)
    {
        try {
            $sql = "SELECT idusuario, nome, email, setor, login
                    FROM usuario
                    WHERE idsupervisor = :idsupervisor
                    ORDER BY nome ASC";

            $stmt = $this->conexao->prepare($sql);
            $stmt->bindValue(':idsupervisor', $idSupervisor, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Erro ao listar subordinados: " . $e->getMessage();
            return [];
        }
    }
}
?>
