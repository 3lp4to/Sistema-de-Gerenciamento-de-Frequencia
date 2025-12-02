<?php
session_start();
require '../vendor/autoload.php';

require '../vendor/autoload.php'; // Certifique-se que PHPMailer está instalado via Composer
include_once "../controller/usuarioDAO.php";


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Só bolsistas podem enviar justificativa
if (!isset($_SESSION['id']) || $_SESSION['tipo'] !== 'bolsista') {
    $_SESSION['msg'] = "Acesso negado!";
    header("Location: ../view/justificativa.php");
    exit;
}

// Verifica CSRF token
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    $_SESSION['msg'] = "Erro de segurança! Tente novamente.";
    header("Location: ../view/justificativa.php");
    exit;
}

// Verifica se o campo justificativa foi enviado
if (!isset($_POST['justificativa']) || empty(trim($_POST['justificativa']))) {
    $_SESSION['msg'] = "Por favor, preencha o campo de justificativa.";
    header("Location: ../view/justificativa.php");
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
        header("Location: ../view/justificativa.php");
        exit;
    }

    // Pega e-mail do supervisor
    $supervisor = $usuarioDAO->buscarPorId($usuarioBolsista['idsupervisor']);
    $destinatario = $supervisor['email'] ?? null;

    if (!$destinatario) {
        $_SESSION['msg'] = "Erro: e-mail do supervisor não encontrado.";
        header("Location: ../view/justificativa.php");
        exit;
    }

    // ---------------------------
    // CONFIGURAÇÃO DO PHPMailer
    // ---------------------------
   // Configura PHPMailer
$mail = new PHPMailer(true);

$mail->CharSet = 'UTF-8';
$mail->Encoding = 'base64';

$mail->isSMTP();
$mail->Host       = 'smtp.gmail.com';
$mail->SMTPAuth   = true;
$mail->Username   = 'gerenciamentoiffar@gmail.com';
$mail->Password   = 'figv qxtx zkuc ssrw';
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->Port       = 587;

$mail->setFrom('gerenciamentoiffarL@gmail.com', 'Ponto Eletrônico IFFar');
$mail->addAddress($destinatario);

$mail->isHTML(false);
$mail->Subject = "Justificativa de Falta/Erro - " . $usuarioBolsista['nome'];
$mail->Body    =
    "O bolsista " . $usuarioBolsista['nome'] .
    " enviou a seguinte justificativa:\n\n" .
    $justificativa;

    // Envia o email
    $mail->send();
    $_SESSION['msg'] = "Justificativa enviada com sucesso para o supervisor.";

} catch (Exception $e) {
    $_SESSION['msg'] = "Erro ao enviar a justificativa: " . $mail->ErrorInfo;
}

header("Location: ../view/justificativa.php");
exit;
