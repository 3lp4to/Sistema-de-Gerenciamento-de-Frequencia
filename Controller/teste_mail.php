<?php
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.seuservidor.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'seuemail@seudominio.com';
    $mail->Password   = 'suasenha';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    $mail->setFrom('no-reply@iffar.edu.br', 'Teste PHPMailer');
    $mail->addAddress('destino@exemplo.com');

    $mail->isHTML(true);
    $mail->Subject = 'Teste de e-mail';
    $mail->Body    = 'Funcionou!';

    $mail->send();
    echo "E-mail enviado com sucesso!";
} catch (Exception $e) {
    echo "Erro ao enviar: {$mail->ErrorInfo}";
}
