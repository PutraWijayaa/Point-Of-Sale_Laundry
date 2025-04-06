<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['ID_USER'])) {
    header("Location: index.php");
    exit();
}

include('koneksi.php');
?>

<!-- <main id="main" class="main"> -->

<div class="pagetitle">
    <h1>Transaction</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.html">Home</a></li>
            <li class="breadcrumb-item">Tables</li>
            <li class="breadcrumb-item active">General</li>
        </ol>
    </nav>
</div><!-- End Page Title -->

<a href="/Point-Of-Sale_Laundry/content/cetak_laporan.php" target="_blank" class="btn btn-dark mb-3">Print PDF</a>

<section class="section">
    <div class="row">

        <div class="col-lg-12">

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Transaction Report</h5>

                    <!-- Table with hoverable rows -->
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Transaction Code</th>
                                <th scope="col">Customer Name</th>
                                <th scope="col">Order Date</th>
                                <th scope="col">PickUp Date</th>
                                <th scope="col">Total</th>
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
            </div>

        </div>
</section>