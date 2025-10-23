<?php
session_start();

include_once "../Conexao/Conexao.php";

if (isset($_POST['btLogin'])) {
    $login = trim($_POST['login']);
    $senha = trim($_POST['senha']);

    // Validação de campos vazios
    if (empty($login) || empty($senha)) {
        header('Location: ../View/login.html');
        exit;
    }

    try {
        $conexao = Conexao::getConexao();
        // Consulta apenas por login
        $stmt = $conexao->prepare("SELECT * FROM usuario WHERE login = :login");
        $stmt->bindValue(":login", $login);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Comparação direta de senha em texto simples
        if ($user && $senha === $user['senha']) {
            $_SESSION['id'] = $user['id'];
            $_SESSION['nome'] = $user['nome'];
            $_SESSION['login'] = $user['login'];
            $_SESSION['senha'] = $user['senha'];

            header('Location: ../View/telainicial.php');
            exit;
        } else {
            header('Location: ../View/login.html');
            exit;
        }
    } catch (PDOException $e) {
        echo "Erro ao conectar ao banco de dados: " . $e->getMessage();
        exit;
    }
}
?>
