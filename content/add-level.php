<?php
if (isset($_POST['save'])) {
    $level_name = $_POST['level_name'];

    $insert = mysqli_query($koneksi, "INSERT INTO level (level_name)
    VALUES('$level_name')");
    if ($insert) {
        header("location:?page=akses&add=success");
    }
}

$id = isset($_GET['edit']) ? $_GET['edit'] : '';
$queryEdit = mysqli_query($koneksi, "SELECT * FROM level WHERE id = '$id'");
$rowEdit = mysqli_fetch_assoc($queryEdit);

if (isset($_POST['edit'])) {
    $id = $_GET['edit'];
    $level_name = $_POST['level_name'];

    // if ($_POST['password']) {
    //     $password = sha1($_POST['password']);
    // } else {
    //     $password = $rowEdit['password'];
    // }

    $update = mysqli_query($koneksi, "UPDATE level 
    SET level_name ='$level_name' WHERE id ='$id'");
    if ($update) {
        header("location:?page=akses&update=success");
    }
}


?>
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <h3><?php echo isset($_GET['edit']) ? 'Edit' : 'Create New' ?> Akses</h3>
            </div>
            <div class="card-body">
                <form action="" method="post">
                    <div class="mb-3">
                        <label for="">Level Name *</label>
                        <input
                            value="<?php echo isset($_GET['edit']) ? $rowEdit['level_name'] : '' ?>"
                            type="text" class="form-control"
                            name="level_name" required placeholder="Enter Level Name">
                    </div>
                    <div class="mb-3">
                        <button class="btn btn-primary" type="submit" name="<?php echo isset($_GET['edit']) ? 'edit' : 'save' ?>">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>