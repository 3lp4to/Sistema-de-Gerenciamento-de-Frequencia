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

    // Configura PHPMailer
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host       = 'smtp.seuservidor.com';   // Coloque o servidor SMTP
    $mail->SMTPAuth   = true;
    $mail->Username   = 'seuemail@seudominio.com'; // Usuário SMTP
    $mail->Password   = 'suasenha';                // Senha SMTP
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;                       // Porta SMTP

    $mail->setFrom('no-reply@iffar.edu.br', 'Ponto Eletrônico IFFar');
    $mail->addAddress($destinatario); // Destinatário (supervisor)

    $mail->isHTML(false); // Texto simples
    $mail->Subject = "Justificativa de Falta/Erro - " . $usuarioBolsista['nome'];
    $mail->Body    = "O bolsista " . $usuarioBolsista['nome'] . " enviou a seguinte justificativa:\n\n" . $justificativa;

    $mail->send();
    $_SESSION['msg'] = "Justificativa enviada com sucesso para o supervisor.";

} catch (Exception $e) {
    $_SESSION['msg'] = "Erro ao enviar a justificativa: " . $mail->ErrorInfo;
}

header("Location: ../view/justificativa.php");
exit;
