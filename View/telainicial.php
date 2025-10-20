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
   <header class="animate-in">
    <h2 class="mb-0">Ponto Eletrônico IFFar</h2>

    <!-- Botão sanduíche -->
    <div class="menu-container">
        <button class="menu-btn" id="menuToggle">
            ☰
        </button>
        <div class="menu-dropdown" id="menuDropdown">
            <a href="#">Justificar falta/Registro incorreto</a>
            <a href="#">Folha ponto</a>
            <a href="../Controller/logout.php">Sair</a>
        </div>
    </div>
</header>


<div class="registro-wrapper">
    <!-- Imagem institucional -->
    <div class="left-panel">
        <img src="../img/fundoiffar.jpeg" alt="IFFar">
    </div>

    <!-- Formulário de ponto -->
    <div class="right-panel">
        <div class="form-box">
            <h4 class="mb-3">Registro de Ponto</h4>

            <form action="#" method="post">
                <input type="submit" name="registro" class="btn btn-primary w-100"
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
<script>
    const menuToggle = document.getElementById('menuToggle');
    const menuDropdown = document.getElementById('menuDropdown');

    // Alterna o menu ao clicar no botão
    menuToggle.addEventListener('click', (e) => {
        e.stopPropagation(); // impede que o clique feche o menu imediatamente
        menuDropdown.classList.toggle('show');
    });

    // Fecha o menu ao clicar fora
    document.addEventListener('click', (e) => {
        if (!menuDropdown.contains(e.target) && !menuToggle.contains(e.target)) {
            menuDropdown.classList.remove('show');
        }
    });
</script>

<!-- Relógio no canto inferior direito -->
<div id="relogio-trabalho"></div>

<script>
// Função para formatar tempo (hh:mm:ss)
function formatarTempo(segundos) {
    const h = String(Math.floor(segundos / 3600)).padStart(2, '0');
    const m = String(Math.floor((segundos % 3600) / 60)).padStart(2, '0');
    const s = String(segundos % 60).padStart(2, '0');
    return `${h}:${m}:${s}`;
}

let intervalo;
const relogio = document.getElementById('relogio-trabalho');

// Verifica se já havia um tempo inicial armazenado
if (sessionStorage.getItem('inicioTrabalho')) {
    iniciarContagem();
}

// Quando a página é recarregada após o registro
<?php if (isset($_POST['registro'])): ?>
    <?php if ($_SESSION['estado'] === 'saida'): ?>
        // Registrou chegada → iniciar contagem
        sessionStorage.setItem('inicioTrabalho', Date.now());
        iniciarContagem();
    <?php else: ?>
        // Registrou saída → parar contagem
        pararContagem();
    <?php endif; ?>
<?php endif; ?>

function iniciarContagem() {
    const inicio = Number(sessionStorage.getItem('inicioTrabalho'));
    if (!inicio) return;

    clearInterval(intervalo);
    intervalo = setInterval(() => {
        const agora = Date.now();
        const diff = Math.floor((agora - inicio) / 1000);
        relogio.textContent = "Tempo de trabalho: " + formatarTempo(diff);
    }, 1000);
}

function pararContagem() {
    clearInterval(intervalo);
    sessionStorage.removeItem('inicioTrabalho');
    relogio.textContent = "Tempo de trabalho: 00:00:00";
}
</script>

</body>

</html>