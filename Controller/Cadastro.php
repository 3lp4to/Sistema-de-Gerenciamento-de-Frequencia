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

    // Verifica campos obrigatórios
    if (empty($nome) || empty($email) || empty($setor) || empty($login) || empty($senha) || empty($confSenha)) {
        echo "<script>alert('Por favor, preencha todos os campos.'); window.location.href='../View/cadastro.php';</script>";
        exit;
    }

    // Verifica se as senhas coincidem
    if ($senha !== $confSenha) {
        echo "<script>alert('As senhas não coincidem!'); window.location.href='../View/cadastro.php';</script>";
        exit;
    }

    // Verifica se o login já está cadastrado
    $usuarioDAO = new UsuarioDAO();
    $usuarioExistente = $usuarioDAO->buscarPorLogin($login);
    if ($usuarioExistente) {
        echo "<script>alert('O login já está em uso!'); window.location.href='../View/cadastro.php';</script>";
        exit;
    }

    // Cria o objeto do usuário (sem criptografia)
    $usuario = new Usuario($nome, $email, $setor, $login, $senha, $supervisorId, 'bolsista');

    // Tenta cadastrar o usuário no banco
    if ($usuarioDAO->cadastrarUsuario($usuario)) {
        echo "<script>alert('Usuário cadastrado com sucesso!'); window.location.href='../View/login.php';</script>";
        exit;
    } else {
        echo "<script>alert('Erro ao cadastrar. Tente novamente.'); window.location.href='../View/cadastro.php';</script>";
        exit;
    }
}
?>
