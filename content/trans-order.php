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

// Ambil order dengan status 0 (Belum Dibayar) dan 1 (Sudah Dibayar)
$query_pending = mysqli_query(
    $koneksi,
    "SELECT `order`.*, customer.customer_name 
     FROM `order` 
     LEFT JOIN customer ON customer.id = `order`.id_customer 
     WHERE `order`.order_status = 0 AND `order`.deleted_at IS NULL 
     ORDER BY `order`.id DESC"
);

$query_paid = mysqli_query(
    $koneksi,
    "SELECT `order`.*, customer.customer_name 
     FROM `order` 
     LEFT JOIN customer ON customer.id = `order`.id_customer 
     WHERE `order`.order_status = 1 AND `order`.deleted_at IS NULL 
     ORDER BY `order`.id DESC"
);

$rows_pending = mysqli_fetch_all($query_pending, MYSQLI_ASSOC);
$rows_paid = mysqli_fetch_all($query_paid, MYSQLI_ASSOC);

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $delete = mysqli_query($koneksi, "DELETE FROM `order` WHERE id = '$id'");
    header("location: ?page=trans-order&notif=success");
}
?>

<div class="card">
    <div class="card-body">
        <h5 class="card-title">Transaksi</h5>

        <div align="right" class="mb-3 mt-3">
                    <a href="?page=add-trans-order" class="btn btn-dark"><i class="bi bi-plus-circle"></i> Pesanan Baru</a>
                </div>

        <ul class="nav nav-tabs nav-tabs-bordered" id="borderedTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="pending-tab" data-bs-toggle="tab" data-bs-target="#bordered-pending"
                    type="button" role="tab" aria-controls="pending" aria-selected="true">Pending Payment</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="paid-tab" data-bs-toggle="tab" data-bs-target="#bordered-paid"
                    type="button" role="tab" aria-controls="paid" aria-selected="false">Finished Payment</button>
            </li>
        </ul>

        <div class="tab-content pt-2" id="borderedTabContent">
            <div class="tab-pane fade show active" id="bordered-pending" role="tabpanel" aria-labelledby="pending-tab">
                <div class="card">
                    <!-- <div class="card-header">
                        <h3 class="text-dark">Belum Dibayar</h3>
                    </div> -->
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Transaksi</th>
                                    <th>Nama Pelanggan</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; foreach ($rows_pending as $row): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= $row['trans_code'] ?></td>
                                    <td><?= $row['customer_name'] ?></td>
                                    <td><span class="badge bg-danger">Belum Dibayar</span></td>
                                    <td>

                                        <a href="?page=payment&order_id=<?= $row['id'] ?>"
                                            class="btn btn-warning btn-sm">Bayar</a>
                                        <a href="?page=trans-order&delete=<?= $row['id'] ?>"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus?')"
                                            class="btn btn-danger btn-sm">Hapus</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="bordered-paid" role="tabpanel" aria-labelledby="paid-tab">
                <div class="card">
                    <!-- <div class="card-header">
                        <h3>Transaksi - Sudah Dibayar</h3>
                    </div> -->
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Transaksi</th>
                                    <th>Nama Pelanggan</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; foreach ($rows_paid as $row): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= $row['trans_code'] ?></td>
                                    <td><?= $row['customer_name'] ?></td>
                                    <td><span class="badge bg-success">Sudah Dibayar</span></td>
                                    <td>
                                        <a href="?page=detail-order&detail=<?= $row['id'] ?>"
                                            class="btn btn-dark btn-sm">Print</a>
                                        
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>