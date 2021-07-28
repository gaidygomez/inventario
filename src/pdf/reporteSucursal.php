<?php

require_once "../../conexion.php";
require_once "./fpdf/fpdf.php";

if (isset($_GET['sucursal'])) {
	$query = "SELECT p.*, ps.cantidad, s.sucursal FROM producto AS p 
		INNER JOIN producto_sucursales AS ps 
			ON p.codproducto = ps.producto_id 
		INNER JOIN sucursales AS s 
			ON ps.sucursal_id = s.idsucursal 
		WHERE ps.sucursal_id = ?";

	$stmt = mysqli_prepare($conexion, $query);

	mysqli_stmt_bind_param($stmt, 'i', $_GET['sucursal']);

	mysqli_stmt_execute($stmt);

	$data = array_map(function ($value) {
		return [
			'id' => $value[0],
			'codigo' => $value[1],
			'descripcion' => $value[2],
			'sabor' => $value[5],
			'cantidad' => $value[8],
			'sucursal' => $value[9]
		];
	}, mysqli_fetch_all(mysqli_stmt_get_result($stmt)));

	$pdf = new FPDF('P', 'mm', 'letter');
	$pdf->AddPage();
	$pdf->SetMargins(10, 10, 10);
	$pdf->SetTitle("Reporte De Productos por Sucursal");
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->Cell(195, 7, 'Myo Vector', 0, 1, 'C');
	$pdf->image("../../assets/img/logo.png", 180, 10, 30, 30, 'PNG');
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(20, 5, utf8_decode("Fecha: "), 0, 0, 'L');
	$pdf->SetFont('Arial', '', 10);
	$pdf->Cell(20, 5, (new DateTime())->format('d/m/Y'), 0, 1, 'L');
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->Cell(20, 5, 'Sucursal: ', 0, 0, 'L');
	$pdf->Cell(20, 5, utf8_decode($data[0]['sucursal']), 0, 0, 'L');
	$pdf->Ln();
	$pdf->Ln();
	$pdf->Ln();
	$pdf->SetFillColor(0, 0, 0);
	$pdf->SetTextColor(255, 255, 255);
	$pdf->Cell(196, 5, "Datos del Reporte", 1, 1, 'C', true);
	$pdf->SetTextColor(0, 0, 0);
	$pdf->Cell(40, 5, 'Id Producto', 0, 0, 'C');
	$pdf->Cell(60, 5, utf8_decode('Código'), 0, 0, 'C');
	$pdf->Cell(60, 5, utf8_decode('Descripción'), 0, 0, 'C');
	//$pdf->Cell(30, 5, 'Sabor', 0, 0, 'C');
	$pdf->Cell(50, 5, 'Cantidad', 0, 1, 'C');
	$pdf->SetFont('Arial', '', 10);

	foreach ($data as $key => $value) {
		$pdf->Cell(35, 5, $value['id'], 0, 0, 'C');
		$pdf->Cell(60, 5, $value['codigo'], 0, 0, 'C');
		$pdf->Cell(60, 5, $value['descripcion'], 0, 0, 'C');
		//$pdf->Cell(30, 5, $value['sabor'], 0, 0, 'C');
		$pdf->Cell(50, 5, $value['cantidad'], 0, 1, 'C');
	}

	$pdf->Output('I', 'Reporte de Productos por Sucursal', true);
}