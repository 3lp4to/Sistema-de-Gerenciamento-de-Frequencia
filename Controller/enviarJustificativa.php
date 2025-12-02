<?php
session_start();
require '../vendor/autoload.php';
include_once "../Controller/UsuarioDAO.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Só bolsistas podem enviar justificativa
if (!isset($_SESSION['id']) || $_SESSION['tipo'] !== 'bolsista') {
    $_SESSION['msg'] = "Acesso negado!";
    header("Location: ../View/justificativa.php");
    exit;
}

// Verifica CSRF token
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    $_SESSION['msg'] = "Erro de segurança! Tente novamente.";
    header("Location: ../View/justificativa.php");
    exit;
}

// Verifica se o campo justificativa foi enviado
if (!isset($_POST['justificativa']) || empty(trim($_POST['justificativa']))) {
    $_SESSION['msg'] = "Por favor, preencha o campo de justificativa.";
    header("Location: ../View/justificativa.php");
    exit;
}

// Sanitiza o texto
$justificativa = trim($_POST['justificativa']);

try {
    $usuarioDAO = new UsuarioDAO();

    // Pega informações do bolsista
    $usuarioBolsista = $usuarioDAO->buscarPorId($_SESSION['id']);
    if (!$usuarioBolsista || empty($usuarioBolsista['idsupervisor'])) {
        $_SESSION['msg'] = "Erro: supervisor não encontrado.";
        header("Location: ../View/justificativa.php");
        exit;
    }

    // Pega e-mail do supervisor
    $supervisor = $usuarioDAO->buscarPorId($usuarioBolsista['idsupervisor']);
    $destinatario = $supervisor['email'] ?? null;

    if (!$destinatario) {
        $_SESSION['msg'] = "Erro: e-mail do supervisor não encontrado.";
        header("Location: ../View/justificativa.php");
        exit;
    }

    // ---------------------------
    // CONFIGURAÇÃO DO PHPMailer
    // ---------------------------
    $mail = new PHPMailer(true);

    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;

    // 🔥 COLOQUE AQUI OS DADOS DO SEU GMAIL
    $mail->Username   = 'gerenciamentoiffar@gmail.com';    // <--- seu Gmail
    $mail->Password   = 'figv qxtx zkuc ssrw';    // <--- sua senha de app

    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    // Remetente
    $mail->setFrom('SEUEMAIL@gmail.com', 'Ponto Eletrônico IFFar');

    // Destinatário (supervisor)
    $mail->addAddress($destinatario);

    // Corpo da mensagem
    $mail->isHTML(false);
    $mail->Subject = "Justificativa de Falta/Erro - " . $usuarioBolsista['nome'];
    $mail->Body    = 
        "O bolsista " . $usuarioBolsista['nome'] . " enviou a seguinte justificativa:\n\n" .
        $justificativa;

    // Envia o email
    $mail->send();
    $_SESSION['msg'] = "Justificativa enviada com sucesso para o supervisor.";

} catch (Exception $e) {
    $_SESSION['msg'] = "Erro ao enviar a justificativa: " . $mail->ErrorInfo;
}

header("Location: ../View/justificativa.php");
exit;
