<?php
session_start();
include_once "../Conexao/Conexao.php"; // Conexão com o banco de dados

if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit;
}

// PERMITIR: admin, supervisor e bolsista
if (!in_array($_SESSION['tipo'], ['admin', 'supervisor', 'bolsista'])) {
    echo "Acesso negado!";
    exit;
}
// Apenas bolsistas podem enviar justificativa
if ($_SESSION['tipo'] !== 'bolsista') {
    $_SESSION['msg'] = "Apenas bolsistas podem enviar justificativas!";
    header('Location: ../View/telainicial.php');
    exit;
}

// Verificar se o formulário foi enviado
if (isset($_POST['justificativa'])) {
    $usuario_id = $_SESSION['id'];
    $texto = trim($_POST['justificativa']);
    $data_envio = date("Y-m-d H:i:s");

    if (empty($texto)) {
        $_SESSION['msg'] = "A justificativa não pode estar vazia!";
        header('Location: ../View/justificativa.php');
        exit;
    }

    if (strlen($texto) > 1000) {
        $_SESSION['msg'] = "A justificativa não pode exceder 1000 caracteres.";
        header('Location: ../View/justificativa.php');
        exit;
    }

    try {
        $conexao = Conexao::getConexao();

        // Inserir justificativa no banco
        $stmt = $conexao->prepare("
            INSERT INTO justificativas (idusuario, texto, data_envio) 
            VALUES (:idusuario, :texto, :data_envio)
        ");
        $stmt->bindValue(':idusuario', $usuario_id);
        $stmt->bindValue(':texto', $texto);
        $stmt->bindValue(':data_envio', $data_envio);
        $stmt->execute();

        // Buscar supervisor do bolsista
        $stmtSup = $conexao->prepare("
            SELECT u.nome, u.email 
            FROM usuario u
            JOIN bolsista_supervisor bs ON bs.supervisor_id = u.idusuario
            WHERE bs.bolsista_id = :idusuario
        ");
        $stmtSup->bindValue(':idusuario', $usuario_id);
        $stmtSup->execute();
        $supervisor = $stmtSup->fetch(PDO::FETCH_ASSOC);

        if ($supervisor) {
            // Enviar e-mail para supervisor
            $para = $supervisor['email'];
            $assunto = "Nova justificativa de " . $_SESSION['nome'];
            $mensagemEmail = "O bolsista " . $_SESSION['nome'] . " enviou a seguinte justificativa:\n\n" . $texto;
            $cabecalho = "From: sistema@iffar.edu.br";

            mail($para, $assunto, $mensagemEmail, $cabecalho);
        }

        $_SESSION['msg'] = "Justificativa enviada com sucesso para o supervisor!";
        header('Location: ../View/justificativa.php');
        exit;

    } catch (PDOException $e) {
        $_SESSION['msg'] = "Erro ao enviar justificativa: " . $e->getMessage();
        header('Location: ../View/justificativa.php');
        exit;
    }
}
?>
