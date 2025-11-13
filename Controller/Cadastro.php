<?php
include_once "../Conexao/Conexao.php";
include_once "../Model/Usuario.php";
include_once "../Controller/UsuarioDAO.php";

// Verifica se o botão de cadastro foi clicado
if (isset($_POST['btCadastrar'])) {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $setor = trim($_POST['setor']);
    $login = trim($_POST['login']);
    $senha = trim($_POST['senha']);
    $confSenha = trim($_POST['confSenha']);

    // Verifica se todos os campos obrigatórios foram preenchidos
    if (empty($nome) || empty($email) || empty($setor) || empty($login) || empty($senha) || empty($confSenha)) {
        echo "<script>alert('Por favor, preencha todos os campos.'); window.location.href='../View/cadastro.php';</script>";
        exit;
    }

    // Valida o formato do e-mail
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Por favor, insira um e-mail válido.'); window.location.href='../View/cadastro.php';</script>";
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

    // Criptografa a senha antes de salvar no banco de dados
    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    // Cria o objeto do usuário (colocando o 'supervisorId' em null, pois pode não ser aplicável no cadastro de bolsistas)
    // Considerando que o 'supervisorId' não foi recebido via formulário, vou definir como null ou algo apropriado.
    $supervisorId = null; // Alterar conforme a lógica do seu sistema

    $usuario = new Usuario($nome, $email, $setor, $login, $senhaHash, $supervisorId, 'bolsista');

    try {
        // Tenta cadastrar o usuário no banco
        if ($usuarioDAO->cadastrarUsuario($usuario)) {
            echo "<script>alert('Usuário cadastrado com sucesso!'); window.location.href='../View/login.php';</script>";
            exit;
        } else {
            echo "<script>alert('Erro ao cadastrar. Tente novamente.'); window.location.href='../View/cadastro.php';</script>";
            exit;
        }
    } catch (Exception $e) {
        echo "<script>alert('Erro ao tentar cadastrar: {$e->getMessage()}'); window.location.href='../View/cadastro.php';</script>";
        exit;
    }
}
?>
