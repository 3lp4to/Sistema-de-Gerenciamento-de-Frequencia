<?php
session_start();

include_once "../Conexao/Conexao.php";

if (isset($_POST['btLogin'])) {
    $login = $_POST['login'];
    $senha = $_POST['senha'];


if (empty($login) || empty($senha)) {
    header('Location: ../View/login.html');
    exit;
}


$conexao = Conexao::getConexao();
$stmt = $conexao->prepare("SELECT * FROM usuario WHERE login = :login");
$stmt->bindValue(":login", $login);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);


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
}


?>