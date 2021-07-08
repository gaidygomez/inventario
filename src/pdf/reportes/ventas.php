<?php
require_once 'fpdf/fpdf.php';


function reporteVentas($datos, $config, $total){
	$pdf = new FPDF();
	$pdf->AddPage();
	$pdf->SetMargins(10, 10, 10);
	$pdf->SetTitle('Reporte de Ventas');
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->Cell(195, 7, 'Myo Vector', 0, 1, 'C');
	$pdf->Image("../../assets/img/logo.png", 180, 10, 30, 30, 'PNG');
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(20, 5, utf8_decode("Teléfono: "), 0, 0, 'L');
	$pdf->SetFont('Arial', '', 10);
	$pdf->Cell(20, 5, $config['telefono'], 0, 1, 'L');
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(20, 5, utf8_decode("Dirección: "), 0, 0, 'L');
	$pdf->SetFont('Arial', '', 10);
	$pdf->Cell(20, 5, utf8_decode($config['direccion']), 0, 1, 'L');
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(20, 5, "Correo: ", 0, 0, 'L');
	$pdf->SetFont('Arial', '', 10);
	$pdf->Cell(20, 5, utf8_decode($config['email']), 0, 1, 'L');
	$pdf->Ln();
	$pdf->SetFillColor(0, 0, 0);
	$pdf->SetTextColor(255, 255, 255);
	$pdf->Cell(196, 5, "Datos de las Facturas", 1, 1, 'C', true);
	$pdf->SetTextColor(0, 0, 0);
	$pdf->Cell(30, 5, 'Factura', 0, 0, 'C');
	$pdf->Cell(50, 5, 'Cliente', 0, 0, 'C');
	$pdf->Cell(50, 5, 'Vendedor', 0, 0, 'C');
	$pdf->Cell(30, 5, 'Total', 0, 0, 'C');
	$pdf->Cell(35, 5, 'Fecha', 0, 1, 'C');
	foreach ($datos as $key => $value) {
		$pdf->Cell(30, 5, $value['factura'], 0, 0, 'C');
		$pdf->Cell(50, 5, $value['cliente_nombre'], 0, 0, 'C');
		$pdf->Cell(50, 5, $value['usuario'], 0, 0, 'C');
		$pdf->Cell(30, 5, $value['total'], 0, 0, 'C');
		$pdf->Cell(35, 5, (new DateTime($value['fecha']))->format('d M Y, h:i a'), 0, 1, 'L');
	}
	$pdf->Ln();
	$pdf->Cell(130, 5, 'Total Vendido', 0, 0, 'L');
	$pdf->Cell(30, 5, $total, 0, 0, 'C');
	$pdf->Cell(35, 5, '', 0, 1, 'L');


	return $pdf->Output('I', 'Reporte de Ventas', true);
}