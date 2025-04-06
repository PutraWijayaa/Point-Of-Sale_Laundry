<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['ID_USER'])) {
    header("Location: index.php");
    exit();
}

if (isset($_POST['save'])) {
    $id_level = $_POST['id_level'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = sha1($_POST['password']);

    $insert = mysqli_query($koneksi, "INSERT INTO users (id_level, name, email, password)
    VALUES('$id_level','$name','$email','$password')");
    if ($insert) {
        header("location:?page=user&add=success");
    }
}

$id = isset($_GET['edit']) ? $_GET['edit'] : '';
$queryEdit = mysqli_query($koneksi, "SELECT * FROM users WHERE id = '$id'");
$rowEdit = mysqli_fetch_assoc($queryEdit);

if (isset($_POST['edit'])) {
    $id = $_GET['edit'];
    $$id_level = $_POST['id_level'];
    $name = $_POST['name'];
    $email = $_POST['email'];

    if ($_POST['password']) {
        $password = sha1($_POST['password']);
    } else {
        $password = $rowEdit['password'];
    }

    $update = mysqli_query($koneksi, "UPDATE users 
    SET id_level ='$id_level', name='$name', email='$email', password='$password' WHERE id ='$id'");
    if ($update) {
        header("location:?page=user&update=success");
    }
}

$queryLevels = mysqli_query($koneksi, "SELECT * FROM levels ORDER BY id DESC");
$rowLevels  = mysqli_fetch_all($queryLevels, MYSQLI_ASSOC);


?>
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <h3><?php echo isset($_GET['edit']) ? 'Edit' : 'Create New' ?> Level</h3>
            </div>
            <div class="card-body">
                <form action="" method="post">
                    <div class="mb-3">
                        <label for="">Level Name *</label>
                        <select name="id_level" id="" class="form-control">
                            <option value="">Choose Level</option>
                            <?php foreach ($rowLevels as $rowLevel): ?>
                                <option <?php echo isset($_GET['edit']) ? ($rowLevel['id'] == $rowEdit['id_level']) ? 'selected' : '' : '' ?> value="<?php echo $rowLevel['id'] ?>"><?php echo $rowLevel['level_name'] ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="">Name *</label>
                        <input
                            value="<?php echo isset($_GET['edit']) ? $rowEdit['name'] : '' ?>"
                            type="text" class="form-control"
                            name="name" required placeholder="Enter  Name">
                    </div>
                    <div class="mb-3">
                        <label for="">Email *</label>
                        <input
                            value="<?php echo isset($_GET['edit']) ? $rowEdit['email'] : '' ?>"
                            type="email" class="form-control"
                            name="email" required placeholder="Enter Level Name">
                    </div>
                    <div class="mb-3">
                        <label for="">Password *</label>
                        <input

                            type="password" class="form-control"
                            name="password" required placeholder="Enter Password">
                    </div>
                    <div class="mb-3">
                        <button class="btn btn-primary" type="submit" name="<?php echo isset($_GET['edit']) ? 'edit' : 'save' ?>">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>