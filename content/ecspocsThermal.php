<?php

require 'vendor/autoload.php';

use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

// 2. Cek session
if (!isset($_SESSION['ID_USER'])) {
    header("Location: index.php");
    exit();
}

// 3. Include koneksi
include('koneksi.php');

// 4. Validasi parameter GET
if (!isset($_GET['detail'])) {
    die("ID transaksi tidak ditemukan.");
}

$id = $_GET['detail'];

// 5. Ambil data transaksi
$query = mysqli_query($koneksi, "SELECT `order`.*, customer.customer_name, customer.address 
    FROM `order` 
    LEFT JOIN customer ON `order`.`id_customer` = customer.id 
    WHERE `order`.id = '$id'");

$transaksi = mysqli_fetch_assoc($query);
if (!$transaksi) {
    die("Transaksi tidak ditemukan.");
}

// 6. Ambil data detail order
$qeue = mysqli_query($koneksi, "SELECT order_detail.*, services.service_name 
    FROM order_detail 
    LEFT JOIN services ON order_detail.id_service = services.id 
    WHERE order_detail.id_order = '$id'");
$details = mysqli_fetch_all($qeue, MYSQLI_ASSOC);

$printerName = "EPSON TM-T20II"; // sesuaikan!

function printer_exists($name) {
    exec("wmic printer get name", $printers);
    foreach ($printers as $printer) {
        if (trim($printer) == $name) {
            return true;
        }
    }
    return false;
}


$useThermal = false;

try {
    $printerName = "EPSON TM-T20II"; // Pastikan nama benar
    $connector = new WindowsPrintConnector($printerName);
    $printer = new Printer($connector);

    // Cetak struk
    $printer->setJustification(Printer::JUSTIFY_CENTER);
    $printer->text("Laundry Bersih\n");
    $printer->text("================================\n");

    $printer->setJustification(Printer::JUSTIFY_LEFT);
    $printer->text("Pelanggan: " . htmlspecialchars($transaksi['customer_name']) . "\n");
    $printer->text("Alamat: " . htmlspecialchars($transaksi['address']) . "\n");
    $printer->text("Tanggal: " . htmlspecialchars($transaksi['order_date']) . "\n");
    $printer->text("================================\n");

    $printer->text("Item          Qty   Subtotal\n");
    $printer->text("--------------------------------\n");
    foreach ($details as $item) {
        $printer->text(
            str_pad(htmlspecialchars($item['service_name']), 14) . 
            str_pad($item['qty'] . " Kg", 6, " ", STR_PAD_LEFT) . 
            "Rp " . number_format($item['subtotal'], 0, ',', '.') . "\n"
        );
    }

    $printer->text("--------------------------------\n");
    $printer->setJustification(Printer::JUSTIFY_RIGHT);
    $printer->text("Total: Rp " . number_format($transaksi['total'], 0, ',', '.') . "\n");
    $printer->text("================================\n");
    $printer->setJustification(Printer::JUSTIFY_CENTER);
    $printer->text("~ Terima kasih ~\n");

    $printer->cut();
    $printer->close();

    $useThermal = true;

} catch (Exception $e) {
    $useThermal = false;
}

?>


<!-- <?php if (!$useThermal): ?> -->
<!-- HTML Print Fallback -->
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Cetak Struk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    body {
        width: 58mm;
        font-family: Arial, sans-serif;
        margin: auto;
    }

    .struk h2 {
        font-size: 18px;
        text-align: center;
        margin-bottom: 5px;
    }

    .total {
        border-top: 1px dashed #000;
        margin-top: 10px;
        padding-top: 5px;
    }

    .thanks {
        text-align: center;
        margin-top: 20px;
    }

    @media print {
        .no-print {
            display: none;
        }
    }
    </style>
</head>

<body onload="window.print()">
    <div class="struk">
        <h2>Laundry Bersih</h2>
        <hr>
        <div>
            <p><strong>Pelanggan:</strong> <?= htmlspecialchars($transaksi['customer_name']) ?></p>
            <p><strong>Alamat:</strong> <?= htmlspecialchars($transaksi['address']) ?></p>
            <p><strong>Tanggal:</strong> <?= htmlspecialchars($transaksi['order_date']) ?></p>
        </div>
        <hr>
        <table class="table table-borderless table-sm" style="font-size: 12px;">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Qty</th>
                    <th class="text-end">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($details as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['service_name']) ?></td>
                    <td><?= $item['qty'] ?> Kg</td>
                    <td class="text-end">Rp <?= number_format($item['subtotal'], 0, ',', '.') ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="total">
            <p class="text-end"><strong>Total: Rp <?= number_format($transaksi['total'], 0, ',', '.') ?></strong></p>
        </div>
        <div class="thanks">
            <p>~ Terima kasih ~</p>
        </div>
    </div>

    <?php if (!$useThermal): ?>
    <script>
        window.onload = function() {
            window.print();
        };
    </script>
<?php endif; ?>

</body>

</html>
<!-- <?php endif; ?> -->