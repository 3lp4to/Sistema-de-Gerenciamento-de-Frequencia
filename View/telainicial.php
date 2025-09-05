<?php
session_start();

if (!isset($_SESSION['id'])) {
    exit;
}

if (!isset($_SESSION['estado'])) {
    $_SESSION['estado'] = 'chegada';
}

$mensagem = '';

if (isset($_POST['registro'])) {
    date_default_timezone_set('America/Sao_Paulo');
    $dataAtual = date("d/m/Y H:i:s");

    if ($_SESSION['estado'] === 'chegada') {
        $mensagem = "<p class='alert alert-success'>Chegada registrada em: $dataAtual</p>";
        $_SESSION['estado'] = 'saida';
    } else {
        $mensagem = "<p class='alert alert-warning'>Saída registrada em: $dataAtual</p>";
        $_SESSION['estado'] = 'chegada';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Ponto - IFFar</title>
    <link rel="stylesheet" href="css/registro.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="registro-wrapper">
    <!-- Imagem institucional -->
    <div class="left-panel">
        <img src="../../img/fundoiffar.jpeg" alt="IFFar">
    </div>

    <!-- Formulário de ponto -->
    <div class="right-panel">
        <div class="form-box">
            <header class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">Ponto Eletrônico IFFar</h2>
                <a href="../Controller/logout.php" class="btn btn-danger">Sair</a>
            </header>

            <h4 class="mb-3">Registro de Ponto</h4>

            <form action="#" method="post">
                <input type="submit" name="registro" 
                       class="btn btn-primary w-100"
                       value="<?= ($_SESSION['estado'] === 'chegada') ? 'Registrar Chegada' : 'Registrar Saída'; ?>">
            </form>

            <div class="mt-3">
                <?= $mensagem ?>
            </div>

            <hr>

            <button class="btn btn-secondary w-100" onclick="pegarLocalizacao()">Testar Localização</button>
        </div>
    </div>
</div>

<script>
function pegarLocalizacao() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (pos) {
            const lat = pos.coords.latitude;
            const lon = pos.coords.longitude;

            fetch('teste.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ latitude: lat, longitude: lon })
            })
            .then(res => res.text())
            .then(data => alert(data));
        }, function (error) {
            alert("Erro ao capturar localização: " + error.message);
        });
    } else {
        alert("Geolocalização não suportada pelo navegador.");
    }
}
</script>

</body>
</html>
