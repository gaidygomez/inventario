<?php

require_once "../../vendor/autoload.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

function reporteVentas($datos, $config, $total) {
    $spreadsheet = new Spreadsheet();

    $sheet = $spreadsheet->getActiveSheet();

    $sheet->mergeCells('B1:D1');
    
    $sheet->getStyle('B1')->getAlignment()->setHorizontal('CENTER');

    $sheet->setCellValue('B1', 'Reporte de Ventas');
    
    return (new Xlsx($spreadsheet))->save('reporte.xlsx');
}