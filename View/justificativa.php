<?php
session_start();

// Verifica se o usuário está logado e se o tipo de usuário é 'admin' ou 'supervisor'
if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit;
}

// PERMITIR: admin, supervisor e bolsista
if (!in_array($_SESSION['tipo'], ['admin', 'supervisor', 'bolsista'])) {
    echo "Acesso negado!";
    exit;
}

// Gera o token CSRF se não existir
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Verifica se há alguma mensagem de erro ou sucesso
$mensagem = '';
if (isset($_SESSION['msg'])) {
    $mensagem = $_SESSION['msg'];
    unset($_SESSION['msg']);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Justificativa - IFFar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h3>Justificar falta / Registro incorreto</h3>
    <p>Preencha a justificativa abaixo e envie para o supervisor.</p>

    <!-- Exibe mensagem caso exista -->
    <?php if (!empty($mensagem)): ?>
        <div class="alert alert-info"><?= htmlspecialchars($mensagem) ?></div>
    <?php endif; ?>

    <form action="../controller/enviarjustificativa.php" method="post" id="formJustificativa">
        <!-- Token CSRF para proteção -->
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">

        <div class="mb-3">
            <label for="justificativa" class="form-label">Justificativa</label>
            <textarea name="justificativa" id="justificativa" rows="5" class="form-control" required placeholder="Descreva a razão do erro ou falta..."></textarea>
        </div>

        <div class="mb-3">
            <input type="submit" value="Enviar" class="btn btn-primary" id="btnEnviar">
            <a href="telainicial.php" class="btn btn-secondary">Voltar</a>
        </div>
    </form>
</div>

<!-- Validação do formulário com JavaScript -->
<script>
    document.getElementById('formJustificativa').addEventListener('submit', function(event) {
        var justificativa = document.getElementById('justificativa').value.trim();

        if (!justificativa) {
            alert('Por favor, preencha o campo de justificativa antes de enviar.');
            event.preventDefault(); // Impede o envio do formulário
        }
    });
</script>
</body>
</html>
