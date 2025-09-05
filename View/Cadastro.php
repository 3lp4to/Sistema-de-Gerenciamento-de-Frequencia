<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Ponto Eletrônico IFFar</title>
    <link rel="stylesheet" href="css/cadastro.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <div class="cadastro-wrapper">
        <!-- Imagem institucional -->
        <div class="left-panel">
            <img src="../img/fundoiffar.jpeg" alt="IFFar">
        </div>

        <!-- Formulário -->
        <div class="right-panel">
            <div class="form-box">
                <h2>Crie sua conta</h2>
                <p>Cadastre-se para registrar sua frequência no sistema do IFFar SVS</p>

                <form action="../Controller/Cadastro.php" method="post">
                    <div class="form-group">
                        <label for="nome">Nome completo</label>
                        <input type="text" name="nome" id="nome" required>
                    </div>

                    <div class="form-group">
                        <label for="email">E-mail institucional</label>
                        <input type="email" name="email" id="email" required>
                    </div>

                    <div class="form-group">
                        <label for="login">Login</label>
                        <input type="text" name="login" id="login" required>
                    </div>

                    <div class="form-group">
                        <label for="senha">Senha</label>
                        <input type="password" name="senha" id="senha" required>
                    </div>

                    <div class="form-group">
                        <label for="confSenha">Confirmar senha</label>
                        <input type="password" name="confSenha" id="confSenha" required>
                    </div>

                    <input type="submit" value="Cadastrar" name="btCadastrar" class="btn-cadastrar">

                    <div class="login-link">
                        <p>Já possui uma conta? <a href="login.php">Faça login</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>
</html>
