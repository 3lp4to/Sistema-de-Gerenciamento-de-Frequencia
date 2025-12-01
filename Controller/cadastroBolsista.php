<?php
session_start();

include_once "../Model/Usuario.php";
include_once "../Controller/UsuarioDAO.php";

// Apenas admin ou supervisor podem cadastrar bolsistas
if (!isset($_SESSION['tipo']) || ($_SESSION['tipo'] != 'supervisor' && $_SESSION['tipo'] != 'admin')) {
    header("Location: ../View/login.php");
    exit;
}

if (isset($_POST['btCadastrar'])) {

    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $setor = trim($_POST['setor']);
    $login = trim($_POST['login']);
    $senha = trim($_POST['senha']);
    $confSenha = trim($_POST['confSenha']);
    $idsupervisor = $_POST['idsupervisor'] ?? null;

    // Verifica campos obrigatórios
    if (empty($nome) || empty($email) || empty($setor) || empty($login) || 
        empty($senha) || empty($confSenha) || empty($idsupervisor)) {
        echo "<script>alert('Por favor, preencha todos os campos.'); window.location.href='../View/cadastroBolsista.php';</script>";
        exit;
    }

    // Validação senha
    if ($senha !== $confSenha) {
        echo "<script>alert('As senhas não coincidem!'); window.location.href='../View/cadastroBolsista.php';</script>";
        exit;
    }

    // Verifica duplicidade de login
    $usuarioDAO = new UsuarioDAO();
    $usuarioExistente = $usuarioDAO->buscarPorLogin($login);

    if ($usuarioExistente) {
        echo "<script>alert('O login já está em uso!'); window.location.href='../View/cadastroBolsista.php';</script>";
        exit;
    }

    // Cria usuário como bolsista
    $usuario = new Usuario(
        $nome,
        $email,
        $setor,
        $login,
        $senha,
        'bolsista',     // tipo
        $idsupervisor   // supervisor responsável
    );

    try {
        if ($usuarioDAO->cadastrarUsuario($usuario)) {
            echo "<script>alert('Bolsista cadastrado com sucesso!'); window.location.href='../View/login.php';</script>";
            exit;
        } else {
            throw new Exception("Erro desconhecido ao cadastrar o bolsista.");
        }
    } catch (Exception $e) {
        echo "<script>alert('Erro ao cadastrar: " . $e->getMessage() . "'); window.location.href='../View/cadastroBolsista.php';</script>";
        exit;
    }
}
