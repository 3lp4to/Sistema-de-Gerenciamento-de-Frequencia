<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Supervisor - Ponto Eletrônico IFFar</title>
    <link rel="stylesheet" href="css/cadastro.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="cadastro-wrapper">
    <div class="left-panel">
        <img src="../img/fundoiffar.jpeg" alt="IFFar">
    </div>

    <div class="right-panel">
        <div class="form-box">
            <h2>Cadastro de Supervisor</h2>
            <p>Cadastre um novo supervisor no sistema do IFFar SVS</p>

            <form action="../Controller/Cadastro.php" method="post" id="formCadastro">
                <div class="form-group mb-3">
                    <label for="nome">Nome completo</label>
                    <input type="text" name="nome" id="nome" required class="form-control">
                </div>

                <div class="form-group mb-3">
                    <label for="email">E-mail institucional</label>
                    <input type="email" name="email" id="email" required class="form-control">
                </div>

                <div class="form-group mb-3">
                    <label for="setor">Setor</label>
                    <input type="text" name="setor" id="setor" placeholder="Ex: Biblioteca, TI, Secretaria..." required class="form-control">
                </div>

                <div class="form-group mb-3">
                    <label for="login">Login</label>
                    <input type="text" name="login" id="login" required class="form-control">
                </div>

                <div class="form-group mb-3">
                    <label for="senha">Senha</label>
                    <input type="password" name="senha" id="senha" required class="form-control">
                </div>

                <div class="form-group mb-3">
                    <label for="confSenha">Confirmar senha</label>
                    <input type="password" name="confSenha" id="confSenha" required class="form-control">
                </div>

                <input type="submit" value="Cadastrar Supervisor" name="btCadastrar" class="btn btn-success w-100">

                <div class="login-link mt-3">
                    <p>Já possui uma conta? <a href="login.php">Faça login</a></p>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('formCadastro').addEventListener('submit', function(event) {
        var senha = document.getElementById('senha').value;
        var confSenha = document.getElementById('confSenha').value;

        if (senha !== confSenha) {
            alert('As senhas não coincidem. Por favor, verifique.');
            event.preventDefault();
        }
    });
</script>

</body>
</html>
