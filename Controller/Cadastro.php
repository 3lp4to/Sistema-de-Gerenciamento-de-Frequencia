<?php
include_once "../Conexao/Conexao.php";
include_once "../Model/Usuario.php";
include_once "../Controller/UsuarioDAO.php";

if (isset($_POST['btCadastrar'])) {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $setor = trim($_POST['setor']);
    $login = trim($_POST['login']);
    $senha = trim($_POST['senha']);
    $confSenha = trim($_POST['confSenha']);

    // Verifica campos obrigatórios e senha
    if (empty($nome) || empty($email) || empty($setor) || empty($login) || empty($senha) || empty($confSenha)) {
        echo "<script>alert('Por favor, preencha todos os campos.'); window.location.href='../View/cadastro.php';</script>";
        exit;
    }

    if ($senha !== $confSenha) {
        echo "<script>alert('As senhas não coincidem!'); window.location.href='../View/cadastro.php';</script>";
        exit;
    }

    // Sem criptografia: usa a senha diretamente
    $usuario = new Usuario($nome, $email, $setor, $login, $senha);
    $usuarioDAO = new UsuarioDAO();

    if ($usuarioDAO->cadastrarUsuario($usuario)) {
        echo "<script>alert('Usuário cadastrado com sucesso!'); window.location.href='../View/login.php';</script>";
        exit;
    } else {
        echo "<script>alert('Erro ao cadastrar. Tente novamente.'); window.location.href='../View/cadastro.php';</script>";
        exit;
    }
}
?>
