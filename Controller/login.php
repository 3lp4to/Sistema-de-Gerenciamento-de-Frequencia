<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit;
}

// Controle de acesso baseado no tipo de usuário
// O admin pode acessar todos os cadastros
if ($_SESSION['tipo'] == 'admin') {
    // Lógica para admin - pode cadastrar supervisor e bolsista
    // Adicione o código do admin aqui
} 
// O supervisor pode cadastrar apenas bolsistas
elseif ($_SESSION['tipo'] == 'supervisor') {
    // Lógica para supervisor - pode cadastrar apenas bolsistas
    // Adicione o código do supervisor aqui
} 
// Bolsista não tem permissão para acessar essas páginas
else {
    echo "Acesso negado!";
    exit;
}

include_once "../Conexao/Conexao.php";

// Aqui você pode continuar com a lógica do formulário de login

if (isset($_POST['btLogin'])) {
    $login = trim($_POST['login']);
    $senha = trim($_POST['senha']);

    // Validação de campos vazios
    if (empty($login) || empty($senha)) {
        $_SESSION['msg_erro'] = "Por favor, preencha todos os campos!";
        header('Location: ../View/login.php');
        exit;
    }

    try {
        $conexao = Conexao::getConexao();
        
        // Consulta por login
        $stmt = $conexao->prepare("SELECT * FROM usuario WHERE login = :login");
        $stmt->bindValue(":login", $login);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verifica se o usuário existe e se a senha confere
        if ($user && $senha === $user['senha']) {
            $_SESSION['id'] = $user['idusuario']; // ajuste se a coluna for idusuario
            $_SESSION['nome'] = $user['nome'];
            $_SESSION['login'] = $user['login'];
            $_SESSION['tipo'] = $user['tipo'];  // Tipo de usuário (admin, supervisor, bolsista)

            // Redireciona para a tela inicial
            header('Location: ../View/telainicial.php');
            exit;
        } else {
            $_SESSION['msg_erro'] = "Login ou senha inválidos!";
            header('Location: ../View/login.php');
            exit;
        }
    } catch (PDOException $e) {
        $_SESSION['msg_erro'] = "Erro ao conectar ao banco de dados: " . $e->getMessage();
        header('Location: ../View/login.php');
        exit;
    }
}
?>
