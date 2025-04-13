<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['ID_USER'])) {
    header("Location: index.php");
    exit();
}

$queryCustomer = mysqli_query($koneksi, "SELECT * FROM customer ORDER BY id DESC");
$rowCustomer = mysqli_fetch_all($queryCustomer, MYSQLI_ASSOC);

// hapus
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $queryDel = mysqli_query($koneksi, "DELETE FROM customer WHERE id = $id");
    header("Location: ?page=customer&notif=success");
    exit();
}

$queryTotal = mysqli_query($koneksi, "SELECT COUNT('id') FROM customer");
$rowTotal = mysqli_fetch_all($queryTotal, MYSQLI_ASSOC);
?>

<!-- HTML Konten -->
<div class="pagetitle">
    <h1>Customer </h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="?pages">Home</a></li>
            <li class="breadcrumb-item">Pages</li>
            <li class="breadcrumb-item">Customer</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <!-- <div class="card-header">
                    <h3 class="text-center text-dark">Customer Data</h3>
                </div> -->
                <div class="card-body">
                    <div align="right" class="mb-3 mt-3">
                        <a href="?page=add-customer" class="btn btn-dark">
                            <i class="bi bi-plus-circle"></i> New Customer
                        </a>
                    </div>
                    <table class="table datatable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Phone</th>
                                <th>Alamat</th>
                                <th hidden>Created At</th>
                                <th hidden>Updated At</th>
                                <th hidden>Deleted At</th>
                                <th>#</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; foreach ($rowCustomer as $row) { ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $row['customer_name'] ?></td>
                                <td><?= $row['phone'] ?></td>
                                <td><?= $row['address'] ?></td>
                                <td hidden><?= $row['created_at'] ?></td>
                                <td hidden><?= $row['updated_at'] ?></td>
                                <td hidden><?= $row['deleted_at'] ?></td>
                                <td class="btn-group">
                                    <a href="?page=add-customer&edit=<?= $row['id'] ?>" class="btn btn-dark btn-sm">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <a href="?page=customer&delete=<?= $row['id'] ?>" 
                                       class="btn btn-danger btn-sm" 
                                       onclick="confirmDelete(event, this)">
                                        <i class="bi bi-trash3-fill"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- SweetAlert Konfirmasi Delete -->
<script>
function confirmDelete(event, el) {
    event.preventDefault();
    const href = el.getAttribute('href');

    Swal.fire({
        title: 'Apakah kamu yakin?',
        text: "Data customer akan dihapus secara permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = href;
        }
    });
}
</script>

<!-- SweetAlert Notifikasi Berhasil Hapus -->
<?php if (isset($_GET['notif']) && $_GET['notif'] == 'success') : ?>
<script>
Swal.fire({
    icon: 'success',
    title: 'Terhapus!',
    text: 'Data customer berhasil dihapus.',
    showConfirmButton: false,
    timer: 2000
});
</script>
<?php endif; ?>
