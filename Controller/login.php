<?php

session_start();

include_once "../conexao/conexao.php";

if (isset($_POST['btLogin'])) {
    $login = trim($_POST['login']);
    $senha = trim($_POST['senha']);

    if (empty($login) || empty($senha)) {
        $_SESSION['msg_erro'] = "Por favor, preencha todos os campos!";
        header('Location: ../view/login.php');
        exit;
    }

    try {
        $conexao = Conexao::getConexao();
        $stmt = $conexao->prepare("SELECT * FROM usuario WHERE login = :login");
        $stmt->bindValue(":login", $login);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && $senha === $user['senha']) {
            // Define as variáveis de sessão
            $_SESSION['id'] = $user['idusuario'];
            $_SESSION['nome'] = $user['nome'];
            $_SESSION['login'] = $user['login'];
            $_SESSION['tipo'] = $user['tipo'];  // <-- AQUI É ONDE VOCÊ SETA O TIPO

            // Redireciona para a tela inicial
            header('Location: ../View/telainicial.php');
            exit;
        } else {
            $_SESSION['msg_erro'] = "Login ou senha inválidos!";
            header('Location: ../view/login.php');
            exit;
        }
    } catch (PDOException $e) {
        $_SESSION['msg_erro'] = "Erro ao conectar ao banco de dados: " . $e->getMessage();
        header('Location: ../view/login.php');
        exit;
    }
}
?>

