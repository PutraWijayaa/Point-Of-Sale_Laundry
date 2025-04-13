<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['ID_USER'])) {
    header("Location: index.php");
    exit();
}

include('koneksi.php');

// Query Data
$queryTransaksi = mysqli_query($koneksi, "SELECT COUNT(id) AS total FROM `order`");
$totalTransaksi = mysqli_fetch_assoc($queryTransaksi)['total'];

$queryPelanggan = mysqli_query($koneksi, "SELECT COUNT(id) AS total FROM customer");
$totalPelanggan = mysqli_fetch_assoc($queryPelanggan)['total'];

$queryPendapatan = mysqli_query($koneksi, "SELECT SUM(total) AS total FROM `order`");
$totalPendapatan = mysqli_fetch_assoc($queryPendapatan)['total'];

$queryLayanan = mysqli_query($koneksi, "SELECT COUNT(id) AS total FROM services");
$totalLayanan = mysqli_fetch_assoc($queryLayanan)['total'];
?>

<section class="section dashboard">
  <div class="row">

    <!-- Total Transaksi -->
    <div class="col-lg-3 col-md-6">
      <div class="card info-card sales-card">
        <div class="card-body">
          <h5 class="card-title">Total Transaksi</h5>
          <div class="d-flex align-items-center">
            <div class="card-icon rounded-circle bg-primary text-white">
              <i class="bi bi-basket-fill"></i>
            </div>
            <div class="ps-3">
              <h6><?= $totalTransaksi; ?></h6>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Total Pelanggan -->
    <div class="col-lg-3 col-md-6">
      <div class="card info-card customers-card">
        <div class="card-body">
          <h5 class="card-title">Total Pelanggan</h5>
          <div class="d-flex align-items-center">
            <div class="card-icon rounded-circle bg-success text-white">
              <i class="bi bi-people-fill"></i>
            </div>
            <div class="ps-3">
              <h6><?= $totalPelanggan; ?></h6>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Total Pendapatan -->
    <div class="col-lg-3 col-md-6">
      <div class="card info-card revenue-card">
        <div class="card-body">
          <h5 class="card-title">Total Pendapatan</h5>
          <div class="d-flex align-items-center">
            <div class="card-icon rounded-circle bg-warning text-white">
              <i class="bi bi-currency-dollar"></i>
            </div>
            <div class="ps-3">
              <h6>Rp. <?= number_format($totalPendapatan, 0, ',', '.'); ?></h6>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Total Layanan -->
    <div class="col-lg-3 col-md-6">
      <div class="card info-card services-card">
        <div class="card-body">
          <h5 class="card-title">Total Layanan</h5>
          <div class="d-flex align-items-center">
            <div class="card-icon rounded-circle bg-danger text-white">
              <i class="bi bi-box-seam"></i>
            </div>
            <div class="ps-3">
              <h6><?= $totalLayanan; ?></h6>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>

  <!-- Chart Section -->
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body pt-4">
          <h5 class="card-title">Analisis Transaksi</h5>
          <canvas id="chartTransaksi" height="120"></canvas>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Load Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const ctx = document.getElementById('chartTransaksi').getContext('2d');
new Chart(ctx, {
  type: 'bar',
  data: {
    labels: ['Transaksi', 'Pelanggan', 'Pendapatan', 'Layanan'],
    datasets: [{
      label: 'Statistik Bisnis',
      data: [<?= $totalTransaksi ?>, <?= $totalPelanggan ?>, <?= $totalPendapatan ?>, <?= $totalLayanan ?>],
      backgroundColor: ['#0d6efd', '#198754', '#ffc107', '#dc3545'],
      borderRadius: 8,
    }]
  },
  options: {
    responsive: true,
    plugins: {
      legend: { display: false }
    },
    scales: {
      y: {
        beginAtZero: true,
        ticks: {
          precision: 0
        }
      }
    }
  }
});
</script>
