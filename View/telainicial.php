<?php
session_start();

if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit;
}

$mensagem = $_SESSION['mensagem'] ?? '';
unset($_SESSION['mensagem']);

if (!isset($_SESSION['estado'])) {
    $_SESSION['estado'] = 'chegada';
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Ponto - IFFar</title>
    <link rel="stylesheet" href="CSS/registro.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        #relogio-trabalho {
            position: fixed;
            right: 20px;
            bottom: 20px;
            background: #198754;
            color: white;
            padding: 10px 18px;
            border-radius: 12px;
            font-size: 18px;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <header class="d-flex justify-content-between align-items-center p-3 bg-success text-white">
        <h2 class="mb-0">Ponto Eletrônico IFFar</h2>
        <div class="menu-container position-relative">
            <button class="menu-btn btn btn-light text-success fw-bold" id="menuToggle">☰ Menu</button>
            <div class="menu-dropdown shadow-lg" id="menuDropdown">
                <?php if ($_SESSION['tipo'] === 'bolsista'): ?>
                    <button class="btn btn-outline-success w-100 my-1" onclick="window.location.href='justificativa.php'">
                        Justificar Falta / Registro Incorreto
                    </button>
                <?php endif; ?>
                <button class="btn btn-outline-success w-100 my-1" id="abrirFolhaPonto">Gerar Folha Ponto (PDF)</button>
                <button class="btn btn-outline-danger w-100 my-1"
                    onclick="window.location.href='../Controller/logout.php'">
                    Sair
                </button>
                <?php if ($_SESSION['tipo'] == 'supervisor'): ?>
                    <hr>
                    <button class="btn btn-outline-primary w-100 my-1"
                        onclick="window.location.href='cadastroBolsista.php'">
                        Cadastrar Bolsista
                    </button>
                <?php elseif ($_SESSION['tipo'] == 'admin'): ?>
                    <hr>
                    <button class="btn btn-outline-primary w-100 my-1" onclick="window.location.href='Cadastro.php'">
                        Cadastrar Supervisor
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <div class="registro-wrapper">
        <div class="left-panel">
            <img src="../img/fundoiffar.jpeg" alt="IFFar">
        </div>

        <div class="right-panel">
            <div class="form-box">
                <h4 class="mb-3">Registro de Ponto</h4>

                <form action="../Controller/cadastroRegistro.php" method="post">
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

        menuToggle.addEventListener('click', e => {
            e.stopPropagation();
            menuDropdown.classList.toggle('show');
        });

        document.addEventListener('click', e => {
            if (!menuDropdown.contains(e.target) && !menuToggle.contains(e.target)) {
                menuDropdown.classList.remove('show');
            }
        });
    </script>

    <div id="relogio-trabalho">Tempo de trabalho: 00:00:00</div>

    <script>
        function formatarTempo(segundos) {
            const h = String(Math.floor(segundos / 3600)).padStart(2, '0');
            const m = String(Math.floor((segundos % 3600) / 60)).padStart(2, '0');
            const s = String(segundos % 60).padStart(2, '0');
            return `${h}:${m}:${s}`;
        }

        let intervalo;
        const relogio = document.getElementById('relogio-trabalho');

        if (sessionStorage.getItem('inicioTrabalho')) iniciarContagem();

        <?php if (isset($_SESSION['acao'])): ?>
            <?php if ($_SESSION['acao'] === 'chegada'): ?>
                sessionStorage.setItem('inicioTrabalho', Date.now());
                iniciarContagem();
            <?php elseif ($_SESSION['acao'] === 'saida'): ?>
                pararContagem();
            <?php endif; ?>
            <?php unset($_SESSION['acao']); ?>
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

    <!-- MODAL FOLHA PONTO -->
    <div class="modal fade" id="modalFolhaPonto" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="../Controller/gerarFolhaPonto.php" method="get" target="_blank">
                    <div class="modal-header">
                        <h5 class="modal-title">Gerar Folha de Ponto</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <label>Mês</label>
                        <input type="number" name="mes" min="1" max="12" class="form-control" required>
                        <label class="mt-3">Ano</label>
                        <input type="number" name="ano" value="<?= date('Y') ?>" class="form-control" required>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-success">Gerar PDF</button>
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('abrirFolhaPonto').addEventListener('click', e => {
            e.preventDefault();
            new bootstrap.Modal(document.getElementById('modalFolhaPonto')).show();
        });
    </script>

</body>

</html>