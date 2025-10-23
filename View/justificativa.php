<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit;
}

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

    <?php if ($mensagem): ?>
        <div class="alert alert-info"><?= $mensagem ?></div>
    <?php endif; ?>

    <form action="../Controller/enviarJustificativa.php" method="post">
        <div class="mb-3">
            <label for="justificativa" class="form-label">Justificativa</label>
            <textarea name="justificativa" id="justificativa" rows="5" class="form-control" required></textarea>
        </div>
        <input type="submit" value="Enviar" class="btn btn-primary">
        <a href="registroPonto.php" class="btn btn-secondary">Voltar</a>
    </form>
</div>
</body>
</html>
