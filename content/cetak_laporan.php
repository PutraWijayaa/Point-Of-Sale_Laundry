<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['ID_USER'])) {
    header("Location: index.php");
    exit();
}

require_once '../vendor/autoload.php';
include('../koneksi.php');

$mpdf = new \Mpdf\Mpdf();

$dateTime = new DateTime();

ob_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Transaction Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header img {
            width: 100px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0;
            font-size: 14px;
        }
        .content {
            margin: 0 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="../assets/img/images.png" alt="Company Logo" width="150px">
        <h1>Laundry Jakarta</h1>
        <p>Jakarta Pusat</p>
        <p>Phone: (123) 456-7890 | Email: info@company.com</p>
    </div>

    <hr>
    <div class="content">
        <h4 style="text-align: left;">Transaction Report</h4>
        <h6>Tanggal Cetak : <?= $dateTime->format('Y-m-d'); ?></h6>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Transaction Code</th>
                    <th>Customer Name</th>
                    <th>Order Date</th>
                    <th>PickUp Date</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = mysqli_query($koneksi, "SELECT `order`.*, customer.customer_name FROM `order` LEFT JOIN customer ON `order`.id_customer = customer.id ORDER BY `order`.order_date DESC");
                $no = 1;
                while ($row = mysqli_fetch_assoc($query)) {
                ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo $row['trans_code']; ?></td>
                        <td><?php echo $row['customer_name']; ?></td>
                        <td><?php echo $row['order_date']; ?></td>
                        <td><?php echo $row['order_end_date']; ?></td>
                        <td>Rp. <?php echo number_format($row['total'], 0, ',', '.'); ?></td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>

</body>
</html>

<?php
$html = ob_get_contents();
ob_end_clean();

$mpdf->WriteHTML($html);

$mpdf->Output('Transaction_Report.pdf', 'I');
exit;
?>