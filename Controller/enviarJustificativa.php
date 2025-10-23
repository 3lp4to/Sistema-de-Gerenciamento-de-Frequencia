<?php
session_start();
include_once "../Conexao/Conexao.php"; // Se for salvar no banco
// include_once "../Model/Justificativa.php"; // Caso tenha uma classe de modelo

if (!isset($_SESSION['id'])) {
    header('Location: ../View/login.php');
    exit;
}

if (isset($_POST['justificativa'])) {
    $usuario_id = $_SESSION['id'];
    $texto = trim($_POST['justificativa']);
    $data_envio = date("Y-m-d H:i:s");

    if (empty($texto)) {
        $_SESSION['msg'] = "A justificativa não pode estar vazia!";
        header('Location: ../View/justificativa.php');
        exit;
    }

    // Exemplo: salvar no banco (tabela "justificativas" com colunas id, usuario_id, texto, data_envio)
    try {
        $conexao = Conexao::getConexao();
        $stmt = $conexao->prepare("INSERT INTO justificativas (usuario_id, texto, data_envio) VALUES (:usuario_id, :texto, :data_envio)");
        $stmt->bindValue(':usuario_id', $usuario_id);
        $stmt->bindValue(':texto', $texto);
        $stmt->bindValue(':data_envio', $data_envio);
        $stmt->execute();

        $_SESSION['msg'] = "Justificativa enviada com sucesso para o supervisor!";
        header('Location: ../View/justificativa.php');
        exit;

    } catch (PDOException $e) {
        $_SESSION['msg'] = "Erro ao enviar justificativa: " . $e->getMessage();
        header('Location: ../View/justificativa.php');
        exit;
    }

    // Alternativa: enviar por e-mail para o supervisor
    // mail($supervisor_email, "Justificativa de $usuario_nome", $texto);
}
?>
