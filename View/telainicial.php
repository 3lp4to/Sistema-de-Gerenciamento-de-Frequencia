<?php
session_start();

// Se não estiver logado, redireciona
if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit;
}

if ($_SESSION['tipo'] == 'admin') {
    echo "Bem-vindo, admin!";
} elseif ($_SESSION['tipo'] == 'supervisor') {
    echo "Bem-vindo, supervisor!";
} elseif ($_SESSION['tipo'] == 'bolsista') {
    echo "Bem-vindo, bolsista!";
} else {
    echo "Acesso negado!";
    exit;
}

// Armazena o estado (chegada/saída) do usuário
if (!isset($_SESSION['estado'])) {
    $_SESSION['estado'] = 'chegada';
}

$mensagem = '';

// Processa o registro de ponto
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
    <link rel="stylesheet" href="CSS/registro.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <header class="d-flex justify-content-between align-items-center p-3 bg-success text-white">
    <h2 class="mb-0">Ponto Eletrônico IFFar</h2>

    <!-- Botão de menu (hambúrguer) -->
    <div class="menu-container position-relative">
        <button class="menu-btn btn btn-light text-success fw-bold" id="menuToggle">
            ☰ Menu
        </button>

        <!-- Menu dropdown animado -->
        <div class="menu-dropdown shadow-lg" id="menuDropdown">
            <!-- Botões gerais -->
            <button class="btn btn-outline-success w-100 my-1" onclick="window.location.href='justificativa.php'">
                Justificar Falta / Registro Incorreto
            </button>
            <button class="btn btn-outline-success w-100 my-1" id="abrirFolhaPonto">
                Gerar Folha Ponto (PDF)
            </button>
            <button class="btn btn-outline-danger w-100 my-1" onclick="window.location.href='../Controller/logout.php'">
                Sair
            </button>

            <!-- Botões por tipo de usuário -->
            <?php if ($_SESSION['tipo'] == 'supervisor'): ?>
                <hr>
                <button class="btn btn-outline-primary w-100 my-1" onclick="window.location.href='cadastroBolsista.php'">
                    Cadastrar Bolsista
                </button>
            <?php elseif ($_SESSION['tipo'] == 'admin'): ?>
                <hr>
                <button class="btn btn-outline-primary w-100 my-1" onclick="window.location.href='cadastroSupervisor.php'">
                    Cadastrar Supervisor
                </button>
            <?php endif; ?>
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

                // Exibir localização ao usuário
                alert(`Latitude: ${lat}\nLongitude: ${lon}`);

                // Enviar a localização para o servidor
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
        <?php if (isset($_SESSION['acao'])): ?>
            <?php if ($_SESSION['acao'] === 'chegada'): ?>
                // Registrou chegada → iniciar contagem
                sessionStorage.setItem('inicioTrabalho', Date.now());
                iniciarContagem();
            <?php else: ?>
                // Registrou saída → parar contagem
                pararContagem();
            <?php endif; ?>
            <?php unset($_SESSION['acao']); // limpa para não repetir ?>
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

    <div class="modal fade" id="modalFolhaPonto" tabindex="-1" aria-labelledby="modalFolhaPontoLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="../Controller/gerarFolhaPonto.php" method="get" target="_blank">
        <div class="modal-header">
          <h5 class="modal-title" id="modalFolhaPontoLabel">Gerar Folha de Ponto</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="mes" class="form-label">Mês</label>
            <input type="number" id="mes" name="mes" min="1" max="12" required class="form-control">
          </div>
          <div class="mb-3">
            <label for="ano" class="form-label">Ano</label>
            <input type="number" id="ano" name="ano" value="<?= date('Y') ?>" required class="form-control">
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Gerar PDF</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
    const abrirFolhaPonto = document.getElementById('abrirFolhaPonto');

    abrirFolhaPonto.addEventListener('click', (e) => {
        e.preventDefault();
        const modal = new bootstrap.Modal(document.getElementById('modalFolhaPonto'));
        modal.show();
    });
</script>

</body>
</html>
