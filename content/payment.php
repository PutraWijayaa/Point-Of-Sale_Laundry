<?php
include('koneksi.php');
if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];
    // Ambil data order berdasarkan ID
    $query = mysqli_query($koneksi, "SELECT o.*, c.customer_name FROM `order` o 
                                      LEFT JOIN customer c ON o.id_customer = c.id 
                                      WHERE o.id = '$order_id' AND o.deleted_at IS NULL");
    $order = mysqli_fetch_assoc($query);
    
    if (!$order) {
        echo "<h3 class='text-danger'>Order tidak ditemukan!</h3>";
        exit;
    }
} else {
    echo "<h3 class='text-danger'>Order ID tidak ditemukan!</h3>";
    exit;
}

// Proses pembayaran
if (isset($_POST['pay'])) {
    $order_pay = $_POST['order_pay'];
    $total = $order['total'];
    $order_change = $order_pay - $total;

    if ($order_pay < $total) {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Pembayaran Gagal',
                text: 'Nominal pembayaran kurang dari total tagihan!'
            });
        </script>";
    } else {
        // Update data pembayaran di database
        $update = mysqli_query($koneksi, "UPDATE `order` SET 
            order_pay = '$order_pay',
            order_change = '$order_change',
            order_status = 1  -- Update status menjadi 'Lunas'
            WHERE id = '$order_id'");

if ($update) {
    echo "<script>
        Swal.fire({
            icon: 'success',
            title: 'Pembayaran Berhasil',
            text: 'Transaksi telah lunas!',
            showConfirmButton: false,
            timer: 1500
        }).then(() => {
             window.location.href = '?page=detail-order&detail=" . $order_id . "';
        });
    </script>";
        } else {
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Gagal memproses pembayaran!'
                });
            </script>";
        }
    }
}
?>

<div class="container">
    <div class="card payment-container">
        <div class="payment-header">
            <h3 class="mb-0">Konfirmasi Pembayaran</h3>
        </div>
        
        <div class="order-details">
            <div class="row">
                <div class="col-6">
                    <p class="mb-2"><strong>Kode Transaksi</strong></p>
                    <p><?php echo htmlspecialchars($order['trans_code']); ?></p>
                </div>
                <div class="col-6">
                    <p class="mb-2"><strong>Nama Pelanggan</strong></p>
                    <p><?php echo htmlspecialchars($order['customer_name']); ?></p>
                </div>
                <div class="col-6">
                    <p class="mb-2"><strong>Tanggal Order</strong></p>
                    <p><?php echo htmlspecialchars($order['order_date']); ?></p>
                </div>
                <div class="col-6">
                    <p class="mb-2"><strong>Total Bayar</strong></p>
                    <p class="text-primary fw-bold">Rp <?php echo number_format($order['total'], 0, ',', '.'); ?></p>
                </div>
            </div>
        </div>
        
        <form method="POST" class="payment-form">
            <div class="mb-3">
                <label for="order_pay" class="form-label">Nominal Pembayaran</label>
                <div class="input-group">
                    <span class="input-group-text">Rp</span>
                    <input 
                        type="number" 
                        name="order_pay" 
                        id="order_pay" 
                        class="form-control" 
                        required 
                        min="<?php echo $order['total']; ?>"
                        placeholder="Masukkan nominal pembayaran"
                    >
                </div>
                <div class="form-text">Minimal pembayaran Rp <?php echo number_format($order['total'], 0, ',', '.'); ?></div>
            </div>
            
            <div class="d-grid gap-2">
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
