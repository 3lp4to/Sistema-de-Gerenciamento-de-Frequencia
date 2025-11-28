<?php
session_start();
date_default_timezone_set('America/Sao_Paulo');

require_once '../lib/fpdf186/fpdf.php';
require_once '../Controller/UsuarioDAO.php';
require_once '../Controller/RegistroDAO.php';

if (!isset($_SESSION['id'])) {
    exit('Usuário não autenticado');
}

$idUsuario = $_SESSION['id'];
$mes = isset($_GET['mes']) ? (int)$_GET['mes'] : date('m');
$ano = isset($_GET['ano']) ? (int)$_GET['ano'] : date('Y');

if ($mes < 1 || $mes > 12) {
    exit('Mês inválido.');
}

if ($ano < 2000 || $ano > date('Y')) {
    exit('Ano inválido.');
}

$usuarioDAO = new UsuarioDAO();
$registroDAO = new RegistroDAO();

$usuario = $usuarioDAO->buscarPorId($idUsuario);
$nomeUsuario = $usuario['nome'] ?? 'Usuário não encontrado';
$setorUsuario = $usuario['setor'] ?? '-';

$registros = $registroDAO->buscarRegistrosPorMes($idUsuario, $mes, $ano);
$totalHoras = $registroDAO->calcularTotalHorasMes($idUsuario, $mes, $ano);

$diasNoMes = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);
$dadosDias = [];
foreach ($registros as $r) {
    $dia = (int)date('d', strtotime($r['dataRegistro']));
    $dadosDias[$dia] = [
        'entrada' => $r['horaChegada'] ?? '',
        'saida' => $r['horaSaida'] ?? '',
        'horas' => $r['horas_trabalhadas'] ?? ''
    ];
}

// Nome do mês por extenso
$nomesMeses = [
    1 => 'Janeiro', 2 => 'Fevereiro', 3 => 'Março', 4 => 'Abril',
    5 => 'Maio', 6 => 'Junho', 7 => 'Julho', 8 => 'Agosto',
    9 => 'Setembro', 10 => 'Outubro', 11 => 'Novembro', 12 => 'Dezembro'
];
$nomeMes = $nomesMeses[$mes];

// ================================================================
// CLASSE EXTENDIDA COM LOGO EM TODAS AS PÁGINAS
// ================================================================
class PDF extends FPDF {
    function Footer() {
        $logoPath = '../View/img/logoiff.png';

        if (file_exists($logoPath)) {
            // Posição a 25–30mm do rodapé
            $this->SetY(-30);
            // Logo no canto inferior direito
            $this->Image($logoPath, 170, $this->GetY(), 25);
        }
    }
}

// Criar PDF usando a classe estendida
$pdf = new PDF('P', 'mm', 'A4');
$pdf->AddPage();
$pdf->SetMargins(15, 15, 15);

// Cabeçalho do PDF
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 7, utf8_decode('INSTITUTO FEDERAL FARROUPILHA - CAMPUS SÃO VICENTE DO SUL'), 0, 1, 'C');
$pdf->Ln(4);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 7, utf8_decode('BOLSA DE ATIVIDADES DE APOIO EDUCACIONAL - ' . $ano), 0, 1, 'C');
$pdf->Ln(2);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 7, utf8_decode('FICHA DE REGISTRO DE ATIVIDADES - ' . $nomeMes), 0, 1, 'C');
$pdf->Ln(10);

$pdf->SetFont('Arial', '', 12);
$pdf->Cell(35, 8, utf8_decode('Nome do aluno:'), 0, 0);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 8, utf8_decode($nomeUsuario), 0, 1);

$pdf->SetFont('Arial', '', 12);
$pdf->Cell(35, 8, utf8_decode('Setor:'), 0, 0);
$pdf->Cell(0, 8, utf8_decode($setorUsuario), 0, 1);
$pdf->Ln(5);

$pdf->SetFont('Arial', 'B', 11);
$pdf->SetFillColor(200, 200, 200);
$pdf->Cell(25, 10, utf8_decode('Dia'), 1, 0, 'C', true);
$pdf->Cell(45, 10, utf8_decode('Entrada'), 1, 0, 'C', true);
$pdf->Cell(45, 10, utf8_decode('Saída'), 1, 0, 'C', true);
$pdf->Cell(50, 10, utf8_decode('Horas Trabalhadas'), 1, 1, 'C', true);

$pdf->SetFont('Arial', '', 11);
for ($dia = 1; $dia <= $diasNoMes; $dia++) {
    $pdf->Cell(25, 8, str_pad($dia, 2, '0', STR_PAD_LEFT), 1, 0, 'C');
    $pdf->Cell(45, 8, $dadosDias[$dia]['entrada'] ?? '', 1, 0, 'C');
    $pdf->Cell(45, 8, $dadosDias[$dia]['saida'] ?? '', 1, 0, 'C');
    $pdf->Cell(50, 8, $dadosDias[$dia]['horas'] ?? '', 1, 1, 'C');
}

$pdf->Ln(4);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, utf8_decode("Total de horas no mês: $totalHoras"), 0, 1, 'R');

// Rodapé
$pdf->Ln(15);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(90, 10, utf8_decode('Assinatura do aluno: ___________________'), 0, 0, 'L');
$pdf->Ln(15);
$pdf->Cell(0, 10, utf8_decode('Carimbo/Assinatura do coordenador do setor: _______________'), 0, 1, 'L');

header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="Folha_Ponto_' . $nomeMes . '_' . $ano . '.pdf"');

// Saída do PDF
$pdf->Output('I', "Folha_Ponto_{$nomeMes}_{$ano}.pdf");
exit;
?>
