<?php

if(empty($_SESSION['click_count']))
{
    $_SESSION['click_count'] = 0;
}


if (isset($_POST['save'])) {
    $id_customer = $_POST['id_customer'];
    $trans_code = $_POST['trans_code'];
    $order_date = $_POST['order_date'];
    $order_end_date = $_POST['order_end_date'];

    // var_dump("INSERT INTO order (id_customer ,trans_code,order_date,order_end_date) VALUES ('$id_customer','$trans_code','$order_date','$order_end_date')");
    // die();

    $insert = mysqli_query($koneksi, "INSERT INTO `order` (id_customer ,trans_code,order_date,order_end_date) VALUES ('$id_customer','$trans_code','$order_date','$order_end_date')");
    
    $id_order = mysqli_insert_id($koneksi);
    $qty = isset($_POST['qty']) ? $_POST['qty'] : [];
    $notes = isset($_POST['notes']) ? $_POST['notes'] : [];
    $service_name = isset($_POST['service_name']) ? $_POST['service_name'] : [];
    $subtotal = $_POST['subtotal'] ? $_POST['subtotal'] : [];

    $total = 0;
    for ($i = 0; $i < $_POST['countDispaly']; $i++) {
        $service = $service_name[$i];
        $cariId_service = mysqli_query($koneksi, "SELECT id FROM services WHERE service_name LIKE '%$service%'");
        $rowid_service = mysqli_fetch_assoc($cariId_service);
        // var_dump($rowid_service);
        // die;

        $id_service = $rowid_service['id'];

        $qty_value = $qty[$i];
        $subtotal_value = $subtotal[$i];
        $notes_value = $notes[$i];

        $instOrderDet = mysqli_query($koneksi, "INSERT INTO order_detail (id_order, id_service, qty, subtotal, notes) VALUES ('$id_order', '$id_service', '$qty_value', '$subtotal_value', '$notes_value')");

        $total += ($subtotal_value * $qty_value);
    }
    $update = mysqli_query($koneksi, "UPDATE order SET total='$total' WHERE id = '$id_order' ");
    header("location: ?page=trans-order&add=success");

}

$id = isset($_GET['edit']) ? $_GET['edit'] : '';
$queryEdit = mysqli_query($koneksi, "SELECT * FROM user WHERE id = '$id'");
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
                                        <option value="0">Choose Service</option>
                                        <?php foreach ($rowService as $rowService): ?>
                                            <option value="<?php echo $rowService['id'] ?>" data-price="<?php echo $rowService['service_price'] ?>"><?php echo $rowService['service_name'] ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="mb-3" align="right">
                            <button type="button" class="btn btn-dark btn-sm add-row"><i class="bi bi-plus-circle"></i></button>
                            <input type="hidden" name="countDispaly" id="countDispaly" value="<?php echo $_SESSION['click_count'] ?>" readonly>
                            </div>
                            <table class="table table-bordered table-order">
                                <thead>
                                    <tr>
                                        <th>No</th>
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