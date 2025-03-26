<?php
include('koneksi.php');
?>

    <div class="container mt-4">
        <h2 class="mb-4">Transaction Report</h2>
        <a href="cetak_laporan.php" target="_blank" class="btn btn-primary">Print PDF</a>

        <table class="table table-bordered">
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

