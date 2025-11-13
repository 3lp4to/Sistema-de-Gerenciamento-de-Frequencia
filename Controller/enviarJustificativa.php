<?php
session_start();
include_once "../Conexao/Conexao.php"; // Conexão com o banco de dados

// Verificar se o usuário está logado
if (!isset($_SESSION['id'])) {
    header('Location: ../View/login.php');
    exit;
}

// Verificar se o formulário foi enviado
if (isset($_POST['justificativa'])) {
    $usuario_id = $_SESSION['id'];
    $texto = trim($_POST['justificativa']);
    $data_envio = date("Y-m-d H:i:s");

    // Verificar se o campo justificativa não está vazio
    if (empty($texto)) {
        $_SESSION['msg'] = "A justificativa não pode estar vazia!";
        header('Location: ../View/justificativa.php');
        exit;
    }

    // Limitar o tamanho da justificativa (opcional)
    $max_tamanho = 1000; // Limite de caracteres
    if (strlen($texto) > $max_tamanho) {
        $_SESSION['msg'] = "A justificativa não pode exceder " . $max_tamanho . " caracteres.";
        header('Location: ../View/justificativa.php');
        exit;
    }

    try {
        // Conectar ao banco de dados
        $conexao = Conexao::getConexao();

        // Preparar e executar o SQL de inserção
        $stmt = $conexao->prepare("INSERT INTO justificativas (usuario_id, texto, data_envio) 
                                   VALUES (:usuario_id, :texto, :data_envio)");

        $stmt->bindValue(':usuario_id', $usuario_id);
        $stmt->bindValue(':texto', $texto);
        $stmt->bindValue(':data_envio', $data_envio);

        // Executar o comando e verificar se foi bem-sucedido
        if ($stmt->execute()) {
            $_SESSION['msg'] = "Justificativa enviada com sucesso para o supervisor!";
        } else {
            $_SESSION['msg'] = "Erro ao enviar justificativa. Tente novamente.";
        }

    } catch (PDOException $e) {
        // Em caso de erro com o banco de dados, exibe a mensagem
        $_SESSION['msg'] = "Erro ao enviar justificativa: " . $e->getMessage();
    }

    // Redirecionar de volta para a página da justificativa
    header('Location: ../View/justificativa.php');
    exit;
}
?>
