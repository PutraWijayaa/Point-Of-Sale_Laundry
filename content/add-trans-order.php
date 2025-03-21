<?php
if (isset($_POST['save'])) {
    $id_level = $_POST['id_level'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = sha1($_POST['password']);

    $insert = mysqli_query($koneksi, "INSERT INTO user (id_level,name,email,password) VALUES ('$id_level','$name','$email','$password')");
    if ($insert) {
        header('location: ?page=user&add=success');
    }
}

$id = isset($_GET['edit']) ? $_GET['edit'] : '';
$queryEdit = mysqli_query($koneksi, "SELECT * FROM user WHERE id = '$id' ");
$rowEdit = mysqli_fetch_assoc($queryEdit);

if (isset($_POST['edit'])) {
    $id = $_GET['edit'];
    $id_level = $_POST['id_level'];
    $name = $_POST['name'];
    $email = $_POST['email'];

    $update = mysqli_query($koneksi, "UPDATE user SET id_name='$id_name', name='$name', email='$email', password='$password'  WHERE id='$id' ");
    if ($update) {
        header("Location:?page=user&update=success");
        # code...
    }
}

$queryCustomers = mysqli_query($koneksi, "SELECT * FROM customer ORDER BY id DESC");
$rowCustomers = mysqli_fetch_all($queryCustomers, MYSQLI_ASSOC);

// query service
$queryService = mysqli_query($koneksi, "SELECT * FROM services ORDER BY id DESC");
$rowService = mysqli_fetch_all($queryService, MYSQLI_ASSOC);

// kode transaksi
$queryTrans = mysqli_query($koneksi, "SELECT max(id) as id FROM `order`");
$rowTrans = mysqli_fetch_assoc($queryTrans);
$id_trans = $rowTrans['id'];
$id_trans++;

$kode_transaksi = "TR/" . date("mdy") . sprintf("/%03d", $id_trans);

?>

<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header text-center mb-3">
                <h3 class="text-dark"><?php echo isset($_GET['edit']) ? 'Edit' : 'Create New' ?> Order</h3>
            </div>
            <!-- <form action="" method="POST"> -->

            <form action="" method="POST">
                <input type="hidden" id="service_price">
                <div class="card-body mt-3">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="mb-3 row">
                                <div class="col-sm-4">
                                    <label for="">Transaksi Code</label>
                                </div>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="trans_code"
                                        value="<?php echo $kode_transaksi ?>" readonly>
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <div class="col-sm-4">
                                    <label for="">Order Date</label>
                                </div>
                                <div class="col-sm-6">
                                    <input type="date" class="form-control" name="order_date">
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="mb-3 row">
                                <div class="col-sm-4">
                                    <label for="">Customer Name</label>
                                </div>
                                <div class="col-sm-6">
                                    <select name="id_customer" id="" class="form-control change">
                                        <option value="">Choose Customer</option>
                                        <?php foreach ($rowCustomers as $rowCustomer): ?>
                                        <option value="<?php echo $rowCustomer['id'] ?>">
                                            <?php echo $rowCustomer['customer_name'] ?>
                                            <?php endforeach ?>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <div class="col-sm-4">
                                    <label for="">Pickup Date</label>
                                </div>
                                <div class="col-sm-6">
                                    <input type="date" class="form-control" name="order_end_date">
                                </div>
                            </div>

                        </div>
                    </div>

                    <!--  -->
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="mb-3 row">
                                <div class="col-sm-4">
                                    <label for=""> Service</label>
                                </div>
                                <div class="col-sm-6">
                                    <select name="id_service" id="id_service" class="form-control change">
                                        <option value="">Choose Service</option>
                                        <?php foreach ($rowService as $rowService): ?>
                                        <option value="<?php echo $rowService['id'] ?>">
                                            <?php echo $rowService['service_name'] ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="mb-3" align="right">
                                <button class="btn btn-danger btn-sm add-row" type="button">Add Row</button>
                            </div>
                            <table class="table table-bordered table-order">
                                <thead>
                                    <tr>
                                        <th>Service</th>
                                        <th>Price</th>
                                        <th>Qty</th>
                                        <th>Notes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="mb-3">
                        <button class="btn btn-dark" type="submit" name="save">Save</button>
                    </div>
                </div>
                <!-- </div> -->
            </form>
        </div>
    </div>
</div>

<!-- </form> -->