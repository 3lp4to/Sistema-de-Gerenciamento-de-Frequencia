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

date_default_timezone_set('America/Sao_Paulo');
$idUsuario = $_SESSION['id'];
$dataAtual = date('Y-m-d');
$horaAtual = date('H:i:s');

try {
    // Criar conexão com o banco
    $conexao = (new Conexao())->getConexao();
    $registroDAO = new RegistroDAO($conexao);

    // Se o estado for "chegada", registrar a chegada
    if ($_SESSION['estado'] === 'chegada') {
        // Criar registro de entrada
        $registro = new Registro($idUsuario, $horaAtual, null, $dataAtual, null);
        
        if ($registroDAO->cadastrarRegistro($registro)) {
            $_SESSION['estado'] = 'saida';
            $_SESSION['mensagem'] = "<p class='alert alert-success'>Chegada registrada em: $horaAtual</p>";
        } else {
            throw new Exception("Erro ao registrar a chegada.");
        }

    } else {
        // Buscar o último registro do usuário para saber horaChegada
        $ultimoRegistro = $registroDAO->buscarUltimoRegistro($idUsuario);

        if ($ultimoRegistro) {
            // Criar registro de saída e calcular horas trabalhadas
            $registro = new Registro(
                $idUsuario,
                $ultimoRegistro['horaChegada'],
                $horaAtual,
                $dataAtual,
                null
            );
            // Registrar a saída
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

    // Alterar a ação na sessão para o próximo estado
    if ($_SESSION['estado'] === 'saida') {
        $_SESSION['acao'] = 'chegada';
    } else {
        $_SESSION['acao'] = 'saida';
    }

    // Redirecionar para a tela inicial com a mensagem
    header('Location: ../View/telainicial.php');
    exit;

} catch (Exception $e) {
    // Caso haja erro, armazenar a mensagem de erro na sessão
    $_SESSION['mensagem'] = "<p class='alert alert-danger'>Erro: " . $e->getMessage() . "</p>";
    header('Location: ../View/telainicial.php');
    exit;
}
?>
