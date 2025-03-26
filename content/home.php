<?php
include('koneksi.php');

// Total transaksi
$queryTransaksi = mysqli_query($koneksi, "SELECT COUNT(id) AS total FROM `order`");
$dataTransaksi = mysqli_fetch_assoc($queryTransaksi);
$totalTransaksi = $dataTransaksi['total'];

// Total pelanggan
$queryPelanggan = mysqli_query($koneksi, "SELECT COUNT(id) AS total FROM customer");
$dataPelanggan = mysqli_fetch_assoc($queryPelanggan);
$totalPelanggan = $dataPelanggan['total'];

// Total pendapatan
$queryPendapatan = mysqli_query($koneksi, "SELECT SUM(total) AS total FROM `order`");
$dataPendapatan = mysqli_fetch_assoc($queryPendapatan);
$totalPendapatan = $dataPendapatan['total'];

// Total layanan
$queryLayanan = mysqli_query($koneksi, "SELECT COUNT(id) AS total FROM services");
$dataLayanan = mysqli_fetch_assoc($queryLayanan);
$totalLayanan = $dataLayanan['total'];
?>

    <div class="container mt-5">
        <h2 class="mb-4">Dashboard Admin</h2>
        <div class="row">
            <div class="col-md-3">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Total Transaksi</h5>
                        <p class="card-text"> <?= $totalTransaksi; ?> </p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-success mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Total Pelanggan</h5>
                        <p class="card-text"> <?= $totalPelanggan; ?> </p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-warning mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Total Pendapatan</h5>
                        <p class="card-text">Rp. <?= number_format($totalPendapatan, 0, ',', '.'); ?> </p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-danger mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Total Layanan</h5>
                        <p class="card-text"> <?= $totalLayanan; ?> </p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card mt-4">
            <div class="card-body">
                <h5 class="card-title">Analisis Transaksi</h5>
                <canvas id="chartTransaksi"></canvas>
            </div>
        </div>
    </div>

    <script>
        const ctx = document.getElementById('chartTransaksi').getContext('2d');
        const chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Transaksi', 'Pelanggan', 'Pendapatan', 'Layanan'],
                datasets: [{
                    label: 'Statistik Bisnis',
                    data: [<?= $totalTransaksi; ?>, <?= $totalPelanggan; ?>, <?= $totalPendapatan; ?>, <?= $totalLayanan; ?>],
                    backgroundColor: ['blue', 'green', 'orange', 'red'],
                }]
            }
        });
    </script>
