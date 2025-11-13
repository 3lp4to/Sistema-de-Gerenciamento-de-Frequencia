<?php
session_start();
require_once '../Model/Registro.php';
require_once 'RegistroDAO.php';
require_once '../Conexao/Conexao.php'; // Classe de conexão PDO

// Verificar se o usuário está logado
if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit;
}

// Bloquear admin de registrar ponto
if ($_SESSION['tipo'] === 'admin') {
    $_SESSION['mensagem'] = "<p class='alert alert-danger'>Administradores não podem registrar ponto.</p>";
    header('Location: ../View/telainicial.php');
    exit;
}

date_default_timezone_set('America/Sao_Paulo');
$idUsuario = $_SESSION['id'];
$dataAtual = date('Y-m-d');
$horaAtual = date('H:i:s');

try {
    $conexao = (new Conexao())->getConexao();
    $registroDAO = new RegistroDAO($conexao);

    // Estado do usuário (chegada ou saída)
    if (!isset($_SESSION['estado'])) {
        $_SESSION['estado'] = 'chegada';
    }

    if ($_SESSION['estado'] === 'chegada') {
        $registro = new Registro($idUsuario, $horaAtual, null, $dataAtual, null);
        if ($registroDAO->cadastrarRegistro($registro)) {
            $_SESSION['estado'] = 'saida';
            $_SESSION['mensagem'] = "<p class='alert alert-success'>Chegada registrada em: $horaAtual</p>";
        } else {
            throw new Exception("Erro ao registrar a chegada.");
        }
    } else {
        $ultimoRegistro = $registroDAO->buscarUltimoRegistro($idUsuario);
        if ($ultimoRegistro) {
            $registro = new Registro($idUsuario, $ultimoRegistro['horaChegada'], $horaAtual, $dataAtual, null);
            if ($registroDAO->registrarSaida($registro)) {
                $_SESSION['estado'] = 'chegada';
                $_SESSION['mensagem'] = "<p class='alert alert-warning'>Saída registrada em: $horaAtual</p>";
            } else {
                throw new Exception("Erro ao registrar a saída.");
            }
        } else {
            throw new Exception("Não foi possível encontrar um registro de chegada válido.");
        }
    }

    // Atualiza ação
    $_SESSION['acao'] = ($_SESSION['estado'] === 'saida') ? 'chegada' : 'saida';

    header('Location: ../View/telainicial.php');
    exit;

} catch (Exception $e) {
    $_SESSION['mensagem'] = "<p class='alert alert-danger'>Erro: " . $e->getMessage() . "</p>";
    header('Location: ../View/telainicial.php');
    exit;
}
?>
