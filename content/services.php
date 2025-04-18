<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['ID_USER'])) {
    header("Location: index.php");
    exit();
}

$queryCustomer = mysqli_query($koneksi, "SELECT * FROM services ORDER BY id DESC");
$rowCustomer = mysqli_fetch_all($queryCustomer, MYSQLI_ASSOC);

?>

<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <h3 class="text-center">- Service Data -</h3>
            </div>

            <div class="card-body">
                <div align="right" class="mb-3 mt-3">
                    <a href="?page=add-service" class="btn btn-dark"><i class="bi bi-plus-circle"></i> New Service</a>
                </div>
                <table class="table datatable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Service Name</th>
                            <th>Service Price</th>
                            <th>Service Description</th>
                            <th hidden>Created At</th>
                            <th hidden>Updated At</th>
                            <th>#</th>
                        </tr>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        foreach ($rowCustomer as $row) { ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $row['service_name'] ?></td>
                            <td><?= $row['service_price'] ?></td>
                            <td><?= $row['service_desc'] ?></td>
                            <td hidden><?= $row['created_at'] ?></td>
                            <td hidden><?= $row['updated_at'] ?></td>
                            <td class="btn-group">
                                <a href="?page=add-service&edit=<?php echo $row['id'] ?>" class="btn btn-dark btn-sm"><i
                                        class="bi bi-pencil-square"></i></a>

                                <button type="button" class="btn btn-danger btn-delete btn-sm" data-id="<?= $row['id'] ?>">
                                    <i class="bi bi-trash"></i>
                                </button>
                            
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- SweetAlert Delete Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const deleteButtons = document.querySelectorAll('.btn-delete');

    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');

            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: "Data akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '?page=services&delete=' + id;
                }
            });
        });
    });
});
</script>

<?php
// Handle delete setelah konfirmasi
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $queryDel = mysqli_query($koneksi, "DELETE FROM services WHERE id = $id");
    echo "<script>
        Swal.fire({
            title: 'Berhasil!',
            text: 'Data berhasil dihapus.',
            icon: 'success',
            timer: 2000,
            showConfirmButton: false
        }).then(() => {
            window.location.href = '?page=services';
        });
    </script>";
    exit();
}
?>