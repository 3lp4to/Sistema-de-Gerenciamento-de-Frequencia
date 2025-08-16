<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="CSS/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <div class="container">
         <h1>Registrar Chegada</h1>
    <form action="#" method="post">
        <input type="submit"name="registro" value="Registrar Chegada">
    </form>
<br>
  <button onclick="pegarLocalizacao()">Testar Localização</button>

    <script>
    function pegarLocalizacao() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(pos) {
                const lat = pos.coords.latitude;
                const lon = pos.coords.longitude;

                fetch('teste.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ latitude: lat, longitude: lon })
                })
                .then(res => res.text())
                .then(data => alert(data));
            }, function(error) {
                alert("Erro ao capturar localização: " + error.message);
            });
        } else {
            alert("Geolocalização não suportada pelo navegador.");
        }
    }
    </script>
</div>
</body>

<?php
if(isset($_POST['registro'])){
    date_default_timezone_set('America/Sao_Paulo');
    $dataAtual = time();
    echo "Data: " . date("d/m/Y H:i:s", $dataAtual);
}
?>
</html>