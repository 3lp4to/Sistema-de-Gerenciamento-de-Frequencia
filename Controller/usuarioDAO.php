<?php

include_once "../model/usuario.php";

class UsuarioDAO
{
    private $conexao;

    public function __construct()
    {
        include_once '../Conexao/Conexao.php';
        $this->conexao = Conexao::getConexao();
        // Garante que o PDO lance exceções
        $this->conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

public function cadastrarUsuario(Usuario $usuario)
{
    try {
        $stmt = $this->conexao->prepare("
            INSERT INTO usuario (nome, email, setor, login, senha, idsupervisor, tipo)
            VALUES (:nome, :email, :setor, :login, :senha, :idsupervisor, :tipo)
        ");

        $stmt->bindValue(":nome", $usuario->getNome());
        $stmt->bindValue(":email", $usuario->getEmail());
        $stmt->bindValue(":setor", $usuario->getSetor());
        $stmt->bindValue(":login", $usuario->getLogin());
        $stmt->bindValue(":senha", $usuario->getSenha());
        $stmt->bindValue(":tipo", $usuario->getTipo()); // <-- AQUI ESTÁ O QUE FALTAVA

        // supervisor não tem supervisor
        $stmt->bindValue(":idsupervisor", null, PDO::PARAM_NULL);

        $stmt->execute();
        return true;

    } catch (PDOException $e) {
        echo "Erro real do banco: " . $e->getMessage();
        return false;
    }
}





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
            error_log($e->getMessage());
            echo "Erro ao buscar o usuário: " . $e->getMessage();
            return null;
        }
    }

    public function listarTodos()
    {
        try {
            $sql = "SELECT idusuario, nome, email, setor, login, idsupervisor 
                    FROM usuario 
                    ORDER BY nome ASC";

            $stmt = $this->conexao->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            echo "Erro ao listar usuários: " . $e->getMessage();
            return [];
        }
    }

    public function buscarPorLogin($login)
    {
        try {
            $sql = "SELECT * FROM usuario WHERE login = :login";
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindValue(':login', $login);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            echo "Erro ao buscar o login: " . $e->getMessage();
            return null;
        }
    }

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
            $stmt->bindValue(":senha", $usuario->getSenha());

            if (is_null($usuario->getIdSupervisor())) {
                $stmt->bindValue(":idsupervisor", null, PDO::PARAM_NULL);
            } else {
                $stmt->bindValue(":idsupervisor", $usuario->getIdSupervisor(), PDO::PARAM_INT);
            }

            $stmt->bindValue(":id", $usuario->getId(), PDO::PARAM_INT);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            echo "Erro ao atualizar o usuário: " . $e->getMessage();
            return false;
        }
    }

    public function excluirUsuario($id)
    {
        try {
            $sql = "DELETE FROM usuario WHERE idusuario = :id";
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            echo "Erro ao excluir o usuário: " . $e->getMessage();
            return false;
        }
    }

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
            error_log($e->getMessage());
            echo "Erro ao listar subordinados: " . $e->getMessage();
            return [];
        }
    }
}
?>
