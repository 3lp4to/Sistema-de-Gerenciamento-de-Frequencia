<?php
include_once "../Model/Usuario.php";
include_once "../Controller/UsuarioDAO.php";

if (isset($_POST['btCadastrar'])) {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $setor = trim($_POST['setor']);
    $login = trim($_POST['login']);
    $senha = trim($_POST['senha']);
    $confSenha = trim($_POST['confSenha']);

    // Verifica campos obrigatórios
    if (empty($nome) || empty($email) || empty($setor) || empty($login) || empty($senha) || empty($confSenha)) {
        echo "<script>alert('Por favor, preencha todos os campos.'); window.location.href='../View/Cadastro.php';</script>";
        exit;
    }

    // Verifica se as senhas coincidem
    if ($senha !== $confSenha) {
        echo "<script>alert('As senhas não coincidem!'); window.location.href='../View/Cadastro.php';</script>";
        exit;
    }

    // Verifica se o login já está cadastrado
    $usuarioDAO = new UsuarioDAO();
    $usuarioExistente = $usuarioDAO->buscarPorLogin($login);
    if ($usuarioExistente) {
        echo "<script>alert('O login já está em uso!'); window.location.href='../View/Cadastro.php';</script>";
        exit;
    }

    // Cria o usuário como SUPERVISOR, idsupervisor = NULL
 $usuario = new Usuario($nome, $email, $setor, $login, $senha, 'supervisor', null);



    try {
        if ($usuarioDAO->cadastrarUsuario($usuario)) {
            echo "<script>alert('Supervisor cadastrado com sucesso!'); window.location.href='../View/login.php';</script>";
            exit;
        } else {
            throw new Exception("Erro desconhecido ao cadastrar o supervisor.");
        }
    } catch (Exception $e) {
        echo "<script>alert('Erro ao cadastrar: " . $e->getMessage() . "'); window.location.href='../View/Cadastro.php';</script>";
        exit;
    }
}
?>
