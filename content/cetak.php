<?php
if (!isset($_SESSION['ID_USER'])) {
    header("Location: index.php");
    exit();
}
include('koneksi.php');

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
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Struk</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            width: 58mm;
            margin: auto;
        }
        .struk {
            text-align: center;
        }
        .struk h2 {
            margin: 0;
        }
        .detail, .total, .pembayaran {
            margin-top: 10px;
            text-align: left;
        }
        .total, .pembayaran {
            border-top: 1px dashed #000;
            padding-top: 5px;
        }
        .thanks {
            margin-top: 20px;
            text-align: center;
        }
        @media print {
            button {
                display: none;
            }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="struk">
        <h2>Laundry Bersih</h2>
        <hr>
        <div class="detail">
            <p><strong>Pelanggan:</strong> <?= htmlspecialchars($transaksi['customer_name']) ?></p>
            <p><strong>Alamat:</strong> <?= htmlspecialchars($transaksi['address']) ?></p>
            <p><strong>Tanggal:</strong> <?= htmlspecialchars($transaksi['order_date']) ?></p>
        </div>
        <hr>
        <div>
            <table style="width: 100%; font-size: 12px;">
                <thead>
                    <tr>
                        <th style="text-align:left;">Item</th>
                        <th>Qty</th>
                        <th style="text-align:right;">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($details as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['service_name']) ?></td>
                        <td><?= $item['qty'] ?> Kg</td>
                        <td style="text-align:right;">Rp <?= number_format($item['subtotal'], 0, ',', '.') ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="total">
            <p style="text-align:right;"><strong>Total: Rp <?= number_format($transaksi['total'], 0, ',', '.') ?></strong></p>
        </div>

        <?php
        // Simulasi uang dibayar (misalnya user bayar Rp 100.000)
        $dibayar = 100000;
        $kembalian = $dibayar - $transaksi['total'];
        ?>

        <div class="pembayaran">
            <p><strong>Uang Dibayar:</strong> Rp <?= number_format($dibayar, 0, ',', '.') ?></p>
            <p><strong>Kembalian:</strong> Rp <?= number_format($kembalian, 0, ',', '.') ?></p>
        </div>

        <div class="thanks">
            <p>~ Terima kasih ~</p>
        </div>
    </div>

    <script>
        window.onload = function() {
            window.print();
        };
    </script>
</body>
</html>
