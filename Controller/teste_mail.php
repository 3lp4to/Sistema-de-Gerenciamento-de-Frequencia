<?php
<<<<<<< HEAD
require '../vendor/autoload.php';
=======
require __DIR__ . '/../vendor/autoload.php';
>>>>>>> 4afaee3d5dc531973b3d134a1634442a2bccad5c

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try {
    // CONFIGURAÇÃO SMTP PARA GMAIL
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'gerenciamentoiffar@gmail.com';     // <-- coloque seu e-mail Gmail
    $mail->Password   = 'figv qxtx zkuc ssrw';      // <-- coloque sua senha de app
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    // REMETENTE
    $mail->setFrom('seuemail@gmail.com', 'Sistema');

    // DESTINATÁRIO
    $mail->addAddress('destino@exemplo.com'); // coloque o destino

    // CONTEÚDO DO E-MAIL
    $mail->isHTML(true);
    $mail->Subject = 'Teste de envio via Gmail';
    $mail->Body    = '<p>E-mail enviado com sucesso via <b>Gmail + PHPMailer</b>!</p>';

    // ENVIO
    $mail->send();
    echo 'E-mail enviado com sucesso!';
    
} catch (Exception $e) {
    echo "Erro ao enviar: {$mail->ErrorInfo}";
}
