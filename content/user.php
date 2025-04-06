<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['ID_USER'])) {
    header("Location: index.php");
    exit();
}

$query = mysqli_query($koneksi, "SELECT users.*, levels.level_name FROM users 
LEFT JOIN levels ON levels.id = users.id_level ORDER BY users.id DESC");
$rows  = mysqli_fetch_all($query, MYSQLI_ASSOC);

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $delete = mysqli_query($koneksi, "DELETE FROM users WHERE id = '$id'");
    header("location:?page=level&notif=success");
}
?>
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <h3>Data User</h3>
            </div>
            <div class="card-body">
                <div align="right" class="mb-3 mt-3">
                    <a href="?page=add-user" class="btn btn-primary">Create New User</a>
                </div>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Level Name</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1;
                        foreach ($rows as $row): ?>
                            <tr>
                                <td><?php echo $no++ ?></td>
                                <td><?php echo $row['level_name'] ?></td>
                                <td><?php echo $row['name'] ?></td>
                                <td><?php echo $row['email'] ?></td>
                                <td>
                                    <a href="?page=add-user&edit=<?php echo $row['id'] ?>" class="btn btn-primary btn-sm">Edit</a>
                                    <a href="?page=user&delete=<?php echo $row['id'] ?>"
                                        onclick="return confirm('Are you sure??')" class="btn btn-danger btn-sm">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>