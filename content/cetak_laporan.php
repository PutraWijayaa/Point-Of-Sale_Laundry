<?php
require_once(__DIR__ . '/../vendor/tecnickcom/tcpdf/tcpdf.php');

include('../koneksi.php');

// Buat instance TCPDF
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Point of Sale Laundry');
$pdf->SetTitle('Transaction Report');
$pdf->SetMargins(10, 10, 10);
$pdf->SetAutoPageBreak(TRUE, 10);
$pdf->AddPage();

// Judul Laporan
$pdf->SetFont('helvetica', 'B', 14);
$pdf->Cell(190, 10, 'Transaction Report', 0, 1, 'C');
$pdf->Ln(5);

// Header Table
$pdf->SetFont('helvetica', 'B', 10);
$pdf->SetFillColor(200, 220, 255);
$pdf->Cell(10, 8, 'No', 1, 0, 'C', true);
$pdf->Cell(40, 8, 'Transaction Code', 1, 0, 'C', true);
$pdf->Cell(40, 8, 'Customer Name', 1, 0, 'C', true);
$pdf->Cell(30, 8, 'Order Date', 1, 0, 'C', true);
$pdf->Cell(30, 8, 'PickUp Date', 1, 0, 'C', true);
$pdf->Cell(30, 8, 'Total', 1, 1, 'C', true);

// Isi Tabel
$pdf->SetFont('helvetica', '', 10);
$query = mysqli_query($koneksi, "SELECT `order`.*, customer.customer_name FROM `order` LEFT JOIN customer ON `order`.id_customer = customer.id ORDER BY `order`.order_date DESC");
$no = 1;

while ($row = mysqli_fetch_assoc($query)) {
    $pdf->Cell(10, 8, $no++, 1, 0, 'C');
    $pdf->Cell(40, 8, $row['trans_code'], 1, 0, 'C');
    $pdf->Cell(40, 8, $row['customer_name'], 1, 0, 'C');
    $pdf->Cell(30, 8, $row['order_date'], 1, 0, 'C');
    $pdf->Cell(30, 8, $row['order_end_date'], 1, 0, 'C');
    $pdf->Cell(30, 8, 'Rp. ' . number_format($row['total'], 0, ',', '.'), 1, 1, 'C');
}

// Output PDF
$pdf->Output('transaction_report.pdf', 'I');
?>
