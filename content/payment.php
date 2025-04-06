<?php
include('koneksi.php');
if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];
    $query = mysqli_query($koneksi, "SELECT o.*, c.customer_name FROM `order` o 
                                      LEFT JOIN customer c ON o.id_customer = c.id 
                                      WHERE o.id = '$order_id' AND o.deleted_at IS NULL");
    $order = mysqli_fetch_assoc($query);
    
    if (!$order) {
        echo "<div class='alert alert-danger'>Order tidak ditemukan!</div>";
        exit;
    }
} else {
    echo "<div class='alert alert-danger'>Order ID tidak ditemukan!</div>";
    exit;
}

if (isset($_POST['pay'])) {
    $order_pay = $_POST['order_pay'];
    $total = $order['total'];
    $order_change = $order_pay - $total;

    if ($order_pay < $total) {
        echo "
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Pembayaran Gagal',
                    text: 'Nominal pembayaran kurang dari total tagihan!'
                });
            });
        </script>";
    } else {
        $update = mysqli_query($koneksi, "UPDATE `order` SET 
            order_pay = '$order_pay',
            order_change = '$order_change',
            order_status = 1
            WHERE id = '$order_id'");

        if ($update) {
            echo "
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Pembayaran Berhasil',
                        text: 'Transaksi telah lunas!',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        window.location.href = '?page=detail-order&detail=$order_id';
                    });
                });
            </script>";
        } else {
            echo "
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Gagal memproses pembayaran!'
                    });
                });
            </script>";
        }
    }
}

?>

<div class="container py-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Konfirmasi Pembayaran</h5>
        </div>
        <div class="card-body">
            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <p class="mb-1 fw-semibold">Kode Transaksi</p>
                    <p><?= htmlspecialchars($order['trans_code']) ?></p>
                </div>
                <div class="col-md-6">
                    <p class="mb-1 fw-semibold">Nama Pelanggan</p>
                    <p><?= htmlspecialchars($order['customer_name']) ?></p>
                </div>
                <div class="col-md-6">
                    <p class="mb-1 fw-semibold">Tanggal Order</p>
                    <p><?= htmlspecialchars($order['order_date']) ?></p>
                </div>
                <div class="col-md-6">
                    <p class="mb-1 fw-semibold">Total Bayar</p>
                    <p class="text-success fw-bold">Rp <?= number_format($order['total'], 0, ',', '.') ?></p>
                </div>
            </div>

            <form method="POST">
                <div class="mb-3">
                    <label for="order_pay" class="form-label fw-semibold">Nominal Pembayaran</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input 
                            type="number" 
                            name="order_pay" 
                            id="order_pay" 
                            class="form-control" 
                            required 
                            min="<?= $order['total'] ?>"
                            placeholder="Masukkan nominal pembayaran"
                        >
                    </div>
                    <div class="form-text">Minimal pembayaran Rp <?= number_format($order['total'], 0, ',', '.') ?></div>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-start">
                    <button type="submit" name="pay" class="btn btn-primary">
                        <i class="bi bi-cash-coin me-2"></i>Bayar Sekarang
                    </button>
                    <a href="?page=trans-order" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

