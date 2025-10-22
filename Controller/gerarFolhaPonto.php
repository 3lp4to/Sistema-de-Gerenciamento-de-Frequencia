<?php
session_start();
date_default_timezone_set('America/Sao_Paulo');
require_once '../lib/fpdf186/fpdf.php';
require_once '../Conexao/Conexao.php';

if (!isset($_SESSION['id'])) {
    exit('Usuário não autenticado');
}

$idUsuario = $_SESSION['id'];
$mes = $_GET['mes'] ?? date('m');
$ano = $_GET['ano'] ?? date('Y');

// Converter número do mês em nome por extenso
$nomesMeses = [
    1 => 'Janeiro', 2 => 'Fevereiro', 3 => 'Março', 4 => 'Abril',
    5 => 'Maio', 6 => 'Junho', 7 => 'Julho', 8 => 'Agosto',
    9 => 'Setembro', 10 => 'Outubro', 11 => 'Novembro', 12 => 'Dezembro'
];
$nomeMes = $nomesMeses[(int)$mes];

// Conectar ao banco
$conexao = (new Conexao())->getConexao();

// Buscar registros do mês
$stmt = $conexao->prepare("
    SELECT dataRegistro, horaChegada, horaSaida, horas_trabalhadas
    FROM registros
    WHERE idusuario = :idusuario
      AND MONTH(dataRegistro) = :mes
      AND YEAR(dataRegistro) = :ano
    ORDER BY dataRegistro ASC
");
$stmt->bindValue(':idusuario', $idUsuario);
$stmt->bindValue(':mes', $mes);
$stmt->bindValue(':ano', $ano);
$stmt->execute();
$registros = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Criar PDF
$pdf = new FPDF();
$pdf->AddPage();

// ===== Cabeçalho =====
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, utf8_decode("Folha de Ponto - $nomeMes de $ano"), 0, 1, 'C');
$pdf->Ln(5);

// Cabeçalho da tabela
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor(0, 100, 0); // Verde escuro (IFFar)
$pdf->SetTextColor(255, 255, 255);
$pdf->Cell(40, 10, utf8_decode('Data'), 1, 0, 'C', true);
$pdf->Cell(40, 10, utf8_decode('Entrada'), 1, 0, 'C', true);
$pdf->Cell(40, 10, utf8_decode('Saída'), 1, 0, 'C', true);
$pdf->Cell(60, 10, utf8_decode('Horas Trabalhadas'), 1, 1, 'C', true);

// Linhas da tabela
$pdf->SetFont('Arial', '', 12);
$pdf->SetTextColor(0, 0, 0);

foreach ($registros as $r) {
    $pdf->Cell(40, 10, date('d/m/Y', strtotime($r['dataRegistro'])), 1, 0, 'C');
    $pdf->Cell(40, 10, $r['horaChegada'], 1, 0, 'C');
    $pdf->Cell(40, 10, $r['horaSaida'] ?: '-', 1, 0, 'C');
    $pdf->Cell(60, 10, $r['horas_trabalhadas'] ?: '-', 1, 1, 'C');
}

// ===== Rodapé =====
$pdf->Ln(10);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, utf8_decode("Gerado em: ") . date('d/m/Y H:i:s'), 0, 1, 'L');

// ===== Logo IFFar =====
$logoPath = '../img/logo_iffar.png'; // ajuste se o nome for diferente
if (file_exists($logoPath)) {
    // Posição no canto inferior direito
    $pdf->Image($logoPath, 160, 260, 30); // x, y, largura
}

// ===== Saída do PDF =====
$pdf->Output('I', "Folha_Ponto_{$nomeMes}_{$ano}.pdf");
