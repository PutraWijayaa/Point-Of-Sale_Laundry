<?php
require __DIR__ . '/../vendor/autoload.php'; // Pastikan Composer sudah terinstall
include('../koneksi.php');

use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;

if (!isset($_GET['detail'])) {
    die("ID transaksi tidak ditemukan.");
}

$id = $_GET['detail'];

// Ambil data transaksi
$query = mysqli_query($koneksi, "SELECT `order`.*, customer.customer_name, customer.address 
    FROM `order` 
    LEFT JOIN customer ON `order`.`id_customer` = customer.id 
    WHERE `order`.id = '$id'");

$transaksi = mysqli_fetch_assoc($query);

if (!$transaksi) {
    die("Transaksi tidak ditemukan.");
}

// Ambil data detail order
$qeue = mysqli_query($koneksi, "SELECT order_detail.*, services.service_name 
    FROM order_detail 
    LEFT JOIN services ON order_detail.id_service = services.id 
    WHERE order_detail.id_order = '$id'");
$details = mysqli_fetch_all($qeue, MYSQLI_ASSOC);

try {
    // Koneksi ke printer (Ganti "POS-58" dengan nama printer yang sesuai)
    $connector = new WindowsPrintConnector("POS-58");

    $printer = new Printer($connector);

    // Cetak header
    $printer->setJustification(Printer::JUSTIFY_CENTER);
    $printer->text("Laundry Bersih\n");
    $printer->text("------------------------\n");

    // Cetak info pelanggan
    $printer->setJustification(Printer::JUSTIFY_LEFT);
    $printer->text("Pelanggan : " . $transaksi['customer_name'] . "\n");
    $printer->text("Alamat    : " . $transaksi['address'] . "\n");
    $printer->text("Tanggal   : " . $transaksi['order_date'] . "\n");
    $printer->text("------------------------\n");

    // Cetak detail pesanan
    $printer->text("Item          Qty     Harga\n");
    $printer->text("------------------------\n");

    foreach ($details as $item) {
        $printer->text(str_pad($item['service_name'], 12) . 
                       str_pad($item['qty'] . " Kg", 6) . 
                       "Rp " . number_format($item['subtotal'], 0, ',', '.') . "\n");
    }

    $printer->text("------------------------\n");

    // Cetak total
    $printer->setJustification(Printer::JUSTIFY_RIGHT);
    $printer->text("Total: Rp " . number_format($transaksi['total'], 0, ',', '.') . "\n\n");

    $printer->setJustification(Printer::JUSTIFY_CENTER);
    $printer->text("Terima Kasih!\n");
    $printer->cut();
    
    $printer->close();

    echo "Struk berhasil dicetak!";
} catch (Exception $e) {
    echo "Gagal mencetak struk: " . $e->getMessage();
}
?>
