<?php
session_start();
require_once '../Model/Registro.php';
require_once 'RegistroDAO.php';
require_once '../Conexao/Conexao.php'; // classe de conexão PDO

if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit;
}

date_default_timezone_set('America/Sao_Paulo');
$idUsuario = $_SESSION['id'];
$dataAtual = date('Y-m-d');
$horaAtual = date('H:i:s');

$conexao = (new Conexao())->getConexao();
$registroDAO = new RegistroDAO($conexao);

if ($_SESSION['estado'] === 'chegada') {
    // Criar registro de entrada
    $registro = new Registro($idUsuario, $horaAtual, null, $dataAtual, null);
    $registroDAO->cadastrarRegistro($registro);

    $_SESSION['estado'] = 'saida';
    $_SESSION['mensagem'] = "<p class='alert alert-success'>Chegada registrada em: $horaAtual</p>";

} else {
    // Buscar o último registro do usuário para saber horaChegada
    $ultimoRegistro = $registroDAO->buscarUltimoRegistro($idUsuario);

    if ($ultimoRegistro) {
        $registro = new Registro(
            $idUsuario,
            $ultimoRegistro['horaChegada'],
            $horaAtual,
            $dataAtual,
            null
        );
        $registroDAO->registrarSaida($registro);

        $_SESSION['estado'] = 'chegada';
        $_SESSION['mensagem'] = "<p class='alert alert-warning'>Saída registrada em: $horaAtual</p>";
    }
}

if ($_SESSION['estado'] === 'saida') {
    $_SESSION['acao'] = 'chegada';
} else {
    $_SESSION['acao'] = 'saida';
}

header('Location: ../View/telainicial.php');
exit;

?>
