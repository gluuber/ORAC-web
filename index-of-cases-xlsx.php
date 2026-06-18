<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

$count = 1;
// Create a new Spreadsheet
$spreadsheet = new Spreadsheet();
$spreadsheet->getProperties()->setCreator("NSW ORAC")->setTitle("Index of Cases");

// Get the active sheet
$sheet = $spreadsheet->getActiveSheet();

// Populate header columns and style them to bold
$sheet->setCellValue('A' . $count, 'ORAC Case ');
$sheet->setCellValue('B' . $count, 'BARC Case');
$sheet->setCellValue('C' . $count, 'IOC English name');
$sheet->setCellValue('D' . $count, 'Scientific name');
$sheet->setCellValue('E' . $count, 'Location');
$sheet->setCellValue('F' . $count, 'Date');
$sheet->setCellValue('G' . $count, 'Decision');
$sheet->getStyle('A' . $count)->getFont()->setBold(true);
$sheet->getStyle('B' . $count)->getFont()->setBold(true);
$sheet->getStyle('C' . $count)->getFont()->setBold(true);
$sheet->getStyle('D' . $count)->getFont()->setBold(true);
$sheet->getStyle('E' . $count)->getFont()->setBold(true);
$sheet->getStyle('F' . $count)->getFont()->setBold(true);
$sheet->getStyle('G' . $count)->getFont()->setBold(true);
$count++;

// Get the data
require_once './mysql.connection.php';

// Create connection
$conn = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DATABASE);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$query = "SELECT * FROM orac_case_list";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    // output data of each row
    while ($row = $result->fetch_assoc()) {
        $date = date_create($row["sightingdate"]);
        $sheet->setCellValue('A' . $count, $row["orac_case_number"]);
        $sheet->setCellValue('B' . $count, $row["barc_case_number"]);
        $sheet->setCellValue('C' . $count, $row["species_name"]);
        $sheet->setCellValue('D' . $count, $row["scientific_name"]);
        $sheet->setCellValue('E' . $count, $row["location"]);
        $sheet->setCellValue('F' . $count, date_format($date, 'j M Y'));
        $sheet->setCellValue('G' . $count, $row["decision"]);
        $count++;
    }
}

// Do some styling
$sheet->getColumnDimension('A')->setWidth(12);
$sheet->getColumnDimension('B')->setWidth(12);
$sheet->getColumnDimension('C')->setWidth(28);
$sheet->getColumnDimension('D')->setWidth(28);
$sheet->getColumnDimension('E')->setWidth(34);
$sheet->getColumnDimension('F')->setWidth(15);
$sheet->getColumnDimension('G')->setWidth(30);
$sheet->freezePane('A2');
$sheet->getStyle('A:A')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('B:B')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('F:F')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('G:G')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

// Create a xlsx writer
$writer = new Xlsx($spreadsheet);

// Save the xlsx file
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="NSW-ORAC_Index-of-Cases.xlsx"');
header('Cache-Control: max-age=0');
$writer->save('php://output');
exit;
