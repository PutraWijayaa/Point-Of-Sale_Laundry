<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['ID_USER'])) {
    header("Location: index.php");
    exit();
}

$queryCustomer = mysqli_query($koneksi, "SELECT * FROM level ORDER BY id DESC");
$rowCustomer = mysqli_fetch_all($queryCustomer, MYSQLI_ASSOC);

?>

<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <h3 class="text-center">- Control Akses -</h3>
            </div>

            <div class="card-body">
                <div align="right" class="mb-3 mt-3">
                    <a href="?page=add-level" class="btn btn-dark"><i class="bi bi-plus-circle"></i> New Control</a>
                </div>
                <table class="table datatable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Name Level</th>
                            <th hidden>Created At</th>
                            <th hidden>Updated At</th>
                            <th hidden>Deleted At</th>
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
                                <td><?= $row['level_name'] ?></td>
                                <td hidden><?= $row['created_at'] ?></td>
                                <td hidden><?= $row['updated_at'] ?></td>
                                <td hidden><?= $row['deleted_at'] ?></td>
                                <td class="btn-group">
                                    <a href="?page=add-level&edit=<?php echo $row['id'] ?>" class="btn btn-dark btn-sm"><i class="bi bi-pencil-square"></i></a>
                                    <a href="?page=customer&delete=<?php echo $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are You Sure?')"><i class="bi bi-trash3-fill"></i></a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>