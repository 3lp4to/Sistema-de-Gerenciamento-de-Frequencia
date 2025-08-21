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
        $mensagem = "<p>Chegada registrada em: $dataAtual</p>";
        $_SESSION['estado'] = 'saida';
    } else {
        $mensagem = "<p>Saída registrada em: $dataAtual</p>";
        $_SESSION['estado'] = 'chegada';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="CSS/styles.css">
</head>
<body>
<header class="animate-in">
    <h1>Ponto Eletrônico IFFar</h1>
    <a href="../Controller/logout.php" class="btn-sair">Sair</a>
</header>
<div class="container">
    <h1>Registro de Ponto</h1>
    <form action="#" method="post">
        <input type="submit" name="registro" 
            value="<?= ($_SESSION['estado'] === 'chegada') ? 'Registrar Chegada' : 'Registrar Saída'; ?>">
    </form>

    <?= $mensagem ?> 

    <br>
    <button onclick="pegarLocalizacao()">Testar Localização</button>
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
