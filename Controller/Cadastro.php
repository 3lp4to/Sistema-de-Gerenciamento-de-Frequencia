<?php
include_once "../Conexao/Conexao.php";
include_once "../Model/usuario.php";
include_once "../Controller/usuarioDAO.php";

if (isset($_POST['btCadastrar'])) {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $login = $_POST['login'];
    $senha = $_POST['senha'];
    $confSenha = $_POST['confSenha'];

    if (!(empty($nome) || empty($email) || empty($login) || empty($senha) || empty($confSenha) || $senha != $confSenha)) {
        $usuario = new usuario($nome, $email,  $login, $senha);
        $usuarioDAO = new usuarioDAO();
        $usuarioDAO->cadastrarUsuario($usuario);
        header('Location: ../View/login.php');
        exit;
    } else {
        header('Location: ../View/cadastro.php');
        exit;
    }
}

?>