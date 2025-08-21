<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="CSS/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>
    <header class="animate-in">
        <h1>Ponto Eletrônico IFFar</h1>
    </header>
    <div class="container">
        <form action="../Controller/login.php" method="post">
            <label for="login">Login</label>
            <input type="text" name="login" id="login" required>

            <label for="senha">Senha:</label>
            <input type="password" name="senha" id="senha" required>

            <input type="submit" value="Login" name="btLogin">

            <p>Não possui um cadastro?</p>
            <a href="cadastro.php">Clique Aqui</a>

        </form>




    </div>
</body>

</html>