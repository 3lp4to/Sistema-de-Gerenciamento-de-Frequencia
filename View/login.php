<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Ponto Eletrônico IFFar</title>
    <link rel="stylesheet" href="css/login.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <div class="login-wrapper">
        <!-- Imagem institucional -->
        <div class="left-panel">
            <img src="../img/fundoiffar.jpeg" alt="IFFar" />
        </div>

        <!-- Formulário -->
        <div class="right-panel">
            <div class="form-box">
                <h2>Bem-vindo</h2>
                <p>Acesse o sistema de ponto eletrônico do IFFar SVS</p>

                <!-- Exibindo mensagens de erro se houver -->
                <?php if (isset($_SESSION['erro_login'])): ?>
                    <div class="alert alert-danger">
                        <?= htmlspecialchars($_SESSION['erro_login']); ?>
                    </div>
                    <?php unset($_SESSION['erro_login']); ?>
                <?php endif; ?>

                <form action="../Controller/login.php" method="post">
                    <div class="form-group">
                        <label for="login">Login</label>
                        <input type="text" id="login" name="login" required aria-label="Login">
                    </div>

                    <div class="form-group">
                        <label for="senha">Senha</label>
                        <input type="password" id="senha" name="senha" required aria-label="Senha">
                    </div>

                    <input type="submit" value="Entrar" name="btLogin" class="btn-login">
                    
                </form>
            </div>
        </div>
    </div>

</body>
</html>
