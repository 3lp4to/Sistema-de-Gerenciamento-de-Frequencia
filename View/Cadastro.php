<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <link rel="stylesheet" href="CSS/styles.css">
</head>
<body>
<header class="animate-in">
    <h1>Ponto Eletrônico IFFar</h1>
    <a href="../Controller/logout.php" class="btn-sair">Sair</a>
    </header>
    <div class="container">
    <form action="../Controller/Cadastro.php" method="post">
        <h1>Cadastra-se</h1>
        <label for="nome">Nome:</label>
        <input type="text" name="nome" id="nome">
         <label for="email">Email:</label>
        <input type="text" name="email" id="email">
        <label for="login">Login:</label>
        <input type="text" name="login" id="login">
        <label for="senha">Senha:</label>
        <input type="password" name="senha" id="senha">
        <label for="confSenha">Confirme sua senha:</label>
        <input type="password" name="confSenha" id="confSenha">
        <input type="submit" value="Cadastrar" name="btCadastrar">
        <a href="login.php">Já possui um cadastro?</a>
    </form>
    </div>
</body>
</html>