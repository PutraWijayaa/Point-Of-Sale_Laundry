<?php
include('koneksi.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['ID_USER'])) {
    header("Location: index.php");
    exit();
}

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

// Format tanggal untuk tampilan
$order_date = new DateTime($order['order_date']);
$formatted_date = $order_date->format('d M Y - H:i');
?>

<!-- Main Content -->
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-flex align-items-center justify-content-between">
                        <h4 class="mb-0">Pembayaran Transaksi</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Transaksi</a></li>
                                <li class="breadcrumb-item active">Pembayaran</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-xl-8 col-lg-7">
                    <div class="card shadow-lg border-0 overflow-hidden">
                        <div class="card-header bg-primary bg-gradient p-3">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="avatar-sm rounded-circle bg-white text-primary">
                                        <i class="bi bi-receipt fs-4 mt-2"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="fw-semibold text-white mb-1">Detail Transaksi</h5>
                                    <p class="text-white-50 mb-0">Kode: <?= htmlspecialchars($order['trans_code']) ?></p>
                                </div>
                                <div class="ribbon ribbon-primary ribbon-shape">
                                    <?= $order['order_status'] == 0 ? 'Belum Lunas' : 'Lunas' ?>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="border-bottom pb-3">
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="flex-shrink-0">
                                                <div class="avatar-xs">
                                                    <div class="avatar-title rounded-circle bg-soft-primary text-primary">
                                                        <i class="bi bi-person"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 ms-2">
                                                <h6 class="text-muted fw-semibold mb-0">Pelanggan</h6>
                                                <p class="mb-0 fs-5"><?= htmlspecialchars($order['customer_name']) ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="border-bottom pb-3">
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="flex-shrink-0">
                                                <div class="avatar-xs">
                                                    <div class="avatar-title rounded-circle bg-soft-primary text-primary">
                                                        <i class="bi bi-calendar-check"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 ms-2">
                                                <h6 class="text-muted fw-semibold mb-0">Tanggal Order</h6>
                                                <p class="mb-0 fs-5"><?= $formatted_date ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <form method="POST" id="paymentForm">
                                    <div class="mb-4">
                                        <label for="order_pay" class="form-label fw-semibold mb-2">Nominal Pembayaran</label>
                                        <div class="input-group input-group-lg shadow-sm">
                                            <span class="input-group-text bg-light border-0">Rp</span>
                                            <input 
                                                type="number" 
                                                name="order_pay" 
                                                id="order_pay" 
                                                class="form-control form-control-lg border-0 bg-light" 
                                                required 
                                                min="<?= $order['total'] ?>"
                                                value="<?= $order['total'] ?>"
                                                placeholder="Masukkan nominal pembayaran"
                                                autofocus
                                            >
                                        </div>
                                        <div class="form-text mt-2">
                                            <i class="bi bi-info-circle me-1 text-primary"></i>
                                            Minimal pembayaran Rp <?= number_format($order['total'], 0, ',', '.') ?>
                                        </div>
                                    </div>

                                    <div class="mb-4" id="changeSection" style="display: none;">
                                        <div class="alert alert-success d-flex align-items-center border-0" role="alert">
                                            <i class="bi bi-cash-coin fs-3 me-2"></i>
                                            <div>
                                                <strong>Kembalian:</strong> 
                                                <span class="fs-5 ms-1" id="changeAmount">Rp 0</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center">
                                        <a href="?page=trans-order" class="btn btn-light btn-lg px-4">
                                            <i class="bi bi-arrow-left me-1"></i> Kembali
                                        </a>
                                        <button type="submit" name="pay" class="btn btn-primary btn-lg px-4 pay-button">
                                            <i class="bi bi-check2-circle me-1"></i> Proses Pembayaran
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-lg-5">
                    <div class="card border-0 shadow-lg">
                        <div class="card-header bg-soft-success p-3">
                            <h5 class="card-title mb-0 text-success">
                                <i class="bi bi-cash me-1"></i> Ringkasan Tagihan
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="table-responsive">
                                <table class="table table-borderless mb-0">
                                    <tbody>
                                        <tr>
                                            <td class="fw-medium">Subtotal</td>
                                            <td class="text-end">Rp <?= number_format($order['total'] * 0.9, 0, ',', '.') ?></td>
                                        </tr>
                                        <tr>
                                            <td class="fw-medium">PPN (10%)</td>
                                            <td class="text-end">Rp <?= number_format($order['total'] * 0.1, 0, ',', '.') ?></td>
                                        </tr>
                                        <tr class="border-top">
                                            <th class="h5 pt-4">Total Bayar</th>
                                            <td class="text-end h4 pt-4 text-success fw-bold">
                                                Rp <?= number_format($order['total'], 0, ',', '.') ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-4">
                                <div class="alert alert-info border-0 d-flex" role="alert">
                                    <div class="flex-shrink-0">
                                        <i class="bi bi-info-circle-fill text-info fs-4"></i>
                                    </div>
                                    <div class="ms-2">
                                        <p class="mb-0">Pilih metode pembayaran dan masukkan nominal untuk menyelesaikan transaksi.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 border-top pt-3">
                                <h6 class="mb-3">Metode Pembayaran</h6>
                                <div class="d-flex flex-wrap gap-2">
                                    <div class="form-check payment-radio">
                                        <input class="form-check-input" type="radio" name="payment_method" id="payment1" checked>
                                        <label class="form-check-label payment-method-label shadow-sm" for="payment1">
                                            <i class="bi bi-cash-stack text-success me-1"></i> Tunai
                                        </label>
                                    </div>
                                    <div class="form-check payment-radio">
                                        <input class="form-check-input" type="radio" name="payment_method" id="payment2">
                                        <label class="form-check-label payment-method-label shadow-sm" for="payment2">
                                            <i class="bi bi-credit-card text-primary me-1"></i> Debit
                                        </label>
                                    </div>
                                    <div class="form-check payment-radio">
                                        <input class="form-check-input" type="radio" name="payment_method" id="payment3">
                                        <label class="form-check-label payment-method-label shadow-sm" for="payment3">
                                            <i class="bi bi-phone text-info me-1"></i> e-Wallet
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-lg mt-4">
                        <div class="card-body p-4">
                            <h6 class="mb-3">Nominal Cepat</h6>
                            <div class="d-flex flex-wrap gap-2 quick-amount-buttons">
                                <button type="button" class="btn btn-outline-primary quick-amount" data-amount="<?= $order['total'] ?>">
                                    Pas
                                </button>
                                <button type="button" class="btn btn-outline-primary quick-amount" data-amount="<?= $order['total'] + 10000 ?>">
                                    +10rb
                                </button>
                                <button type="button" class="btn btn-outline-primary quick-amount" data-amount="<?= $order['total'] + 50000 ?>">
                                    +50rb
                                </button>
                                <button type="button" class="btn btn-outline-primary quick-amount" data-amount="<?= $order['total'] + 100000 ?>">
                                    +100rb
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const orderPayInput = document.getElementById('order_pay');
    const totalAmount = <?= $order['total'] ?>;
    const changeSection = document.getElementById('changeSection');
    const changeAmountDisplay = document.getElementById('changeAmount');
    const quickAmountButtons = document.querySelectorAll('.quick-amount');
    
    // Format number to currency
    function formatCurrency(number) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number);
    }
    
    // Calculate change
    function calculateChange() {
        const payAmount = parseInt(orderPayInput.value) || 0;
        const changeAmount = payAmount - totalAmount;
        
        if (changeAmount >= 0) {
            changeAmountDisplay.textContent = formatCurrency(changeAmount).replace('IDR', 'Rp');
            changeSection.style.display = 'block';
            orderPayInput.classList.remove('is-invalid');
            orderPayInput.classList.add('is-valid');
        } else {
            changeSection.style.display = 'none';
            orderPayInput.classList.remove('is-valid');
            orderPayInput.classList.add('is-invalid');
        }
    }
    
    // Set quick amounts
    quickAmountButtons.forEach(button => {
        button.addEventListener('click', function() {
            const amount = this.getAttribute('data-amount');
            orderPayInput.value = amount;
            calculateChange();
        });
    });
    
    // Calculate on input change
    if(orderPayInput) {
        orderPayInput.addEventListener('input', calculateChange);
        
        // Initial calculation
        calculateChange();
    }
    
    // Payment method selection effect
    const paymentRadios = document.querySelectorAll('input[name="payment_method"]');
    paymentRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            document.querySelectorAll('.payment-method-label').forEach(label => {
                label.classList.remove('active');
            });
            if(this.checked) {
                this.nextElementSibling.classList.add('active');
            }
        });
    });
});
</script>