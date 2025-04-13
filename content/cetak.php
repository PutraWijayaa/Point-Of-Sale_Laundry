<?php

include('../koneksi.php');

if (!isset($_GET['detail'])) {
    die("ID transaksi tidak ditemukan.");
}

$id = $_GET['detail'];

// Get transaction data
$query = mysqli_query($koneksi, "SELECT `order`.*, customer.customer_name, customer.address 
    FROM `order` 
    LEFT JOIN customer ON `order`.`id_customer` = customer.id 
    WHERE `order`.id = '$id'");
$transaksi = mysqli_fetch_assoc($query);

if (!$transaksi) {
    die("Transaksi tidak ditemukan.");
}

// Get detail data
$qeue = mysqli_query($koneksi, "SELECT order_detail.*, services.service_name 
    FROM order_detail 
    LEFT JOIN services ON order_detail.id_service = services.id 
    WHERE order_detail.id_order = '$id'");
$details = mysqli_fetch_all($qeue, MYSQLI_ASSOC);

// Format date
$tanggal = date('d/m/Y H:i', strtotime($transaksi['order_date']));
$tanggal_selesai = date('d/m/Y', strtotime($transaksi['order_end_date']));

// Function to format currency
function formatRupiah($angka) {
    return number_format($angka, 0, ',', '.');
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Laundry</title>
    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    @page {
        size: 58mm 100%; /* Mengatur ukuran kertas secara eksplisit untuk printer thermal */
        margin: 0;
    }

    body {
        font-family: 'Courier New', monospace;
        width: 58mm;
        margin: 0 auto;
        padding: 1mm;
        font-size: 9pt;
        line-height: 1.2;
    }

    .print-container {
        width: 100%;
    }

    .header {
        text-align: center;
        margin-bottom: 3mm;
    }

    .header .logo-text {
        font-size: 12pt;
        font-weight: bold;
    }

    .header .address {
        font-size: 8pt;
    }

    .divider {
        border-top: 1px dashed #000;
        margin: 2mm 0;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 1mm;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 8pt;
    }

    table th,
    table td {
        text-align: left;
        padding: 0.5mm 0;
    }

    table th:last-child,
    table td:last-child {
        text-align: right;
    }

    table .qty {
        text-align: center;
    }

    .total-section {
        margin-top: 2mm;
    }

    .footer {
        text-align: center;
        margin-top: 4mm;
        font-size: 8pt;
    }

    /* For non-print view */
    .no-print {
        display: none;
    }

    @media screen {
        body {
            background-color: #f0f0f0;
            padding: 20px;
        }

        .print-container {
            background-color: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 10px;
            margin: 0 auto;
        }

        .no-print {
            display: block;
            text-align: center;
            margin-top: 20px;
        }

        .no-print button {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-bottom: 10px
        }
    }

    @media print {
        @page {
            size: 58mm auto; /* Memastikan ukuran termal di media print juga */
            margin: 0;
        }

        body {
            width: 56mm; /* Sedikit lebih kecil untuk menghindari overflow */
            margin: 0;
            padding: 1mm;
        }
        
        .print-container {
            width: 100%;
        }
    }
    </style>
</head>

<body>
    <div class="print-container">
        <div class="header">
            <div class="logo-text">LAUNDRY BERSIH</div>
            <div class="address">Jl. Kebersihan No. 123</div>
            <div>Telp: 0812-3456-7890</div>
        </div>

        <div class="divider"></div>

        <div class="info">
            <div class="info-row">
                <span>No. Transaksi:</span>
                <span><?= htmlspecialchars($transaksi['trans_code']) ?></span>
            </div>
            <div class="info-row">
                <span>Tanggal:</span>
                <span><?= $tanggal ?></span>
            </div>
            <div class="info-row">
                <span>Selesai:</span>
                <span><?= $tanggal_selesai ?></span>
            </div>
            <div class="info-row">
                <span>Kasir:</span>
                <span><?= $_SESSION['NAME'] ?? 'Admin' ?></span>
            </div>
        </div>

        <div class="divider"></div>

        <div>
            <div>Pelanggan: <?= htmlspecialchars($transaksi['customer_name']) ?></div>
            <?php if (!empty($transaksi['address'])): ?>
            <div>Alamat: <?= htmlspecialchars($transaksi['address']) ?></div>
            <?php endif; ?>
        </div>

        <div class="divider"></div>

        <table>
            <thead>
                <tr>
                    <th>Item</th>
                    <th class="qty">Qty</th>
                    <th>Harga</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($details as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['service_name']) ?></td>
                    <td class="qty"><?= $item['qty'] ?> Kg</td>
                    <td><?= formatRupiah($item['subtotal']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="divider"></div>

        <div>
            <div class="info-row">
                <span><strong>TOTAL</strong></span>
                <span><strong>Rp <?= formatRupiah($transaksi['total']) ?></strong></span>
            </div>

            <?php if ($transaksi['order_status'] == 1): ?>
            <div class="info-row">
                <span>TUNAI</span>
                <span>Rp <?= formatRupiah($transaksi['order_pay']) ?></span>
            </div>
            <div class="info-row">
                <span>KEMBALI</span>
                <span>Rp <?= formatRupiah($transaksi['order_change']) ?></span>
            </div>
            <?php else: ?>
            <div class="info-row">
                <span><em>Belum Lunas</em></span>
                <span></span>
            </div>
            <?php endif; ?>
        </div>

        <div class="divider"></div>

        <div class="footer">
            <div>===== TERIMA KASIH =====</div>
            <div>Silahkan datang kembali</div>
        </div>
    </div>

    <div class="no-print">
        <button onclick="window.print();">Cetak Struk</button>
        <button onclick="window.close();">Tutup</button>
    </div>

    <script>
    // Auto print on page load with slight delay
    window.addEventListener('load', function() {
        setTimeout(function() {
            window.print();
        }, 500);
    });
    
    // Mengatur pengaturan print sebelum mencetak
    function setPrintOptions() {
        const style = document.createElement('style');
        style.innerHTML = '@page { size: 58mm auto !important; margin: 0 !important; }';
        document.head.appendChild(style);
    }
    
    // Panggil fungsi sebelum print dialog muncul
    window.addEventListener('beforeprint', setPrintOptions);
    </script>
</body>

</html>