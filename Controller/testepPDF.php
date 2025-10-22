<?php
require_once '../lib/fpdf186/fpdf.php';

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);
$pdf->Cell(0,10,'Teste de Geracao de PDF com FPDF!',0,1,'C');
$pdf->Output();
