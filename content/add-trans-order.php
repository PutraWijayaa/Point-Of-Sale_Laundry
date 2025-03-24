<?php

if(empty($_SESSION['click_count']))
{
    $_SESSION['click_count'] = 0;
}


if (isset($_POST['save'])) {
    $id_customer = $_POST['id_customer'];
    $trans_code = $_POST['trans_code'];
    $order_date = $_POST['order_date'];
    $order_end_date = sha1($_POST['order_end_date']);

    // var_dump("INSERT INTO order (id_customer ,trans_code,order_date,order_end_date) VALUES ('$id_customer','$trans_code','$order_date','$order_end_date')");
    // die();

    $insert = mysqli_query($koneksi, "INSERT INTO `order` (id_customer ,trans_code,order_date,order_end_date) VALUES ('$id_customer','$trans_code','$order_date','$order_end_date')");
    if ($insert) {
        
    $id_order = mysqli_insert_id($koneksi);
    $qty = isset($_POST['qty']) ? $_POST['qty'] : 0;
    $notes = isset($_POST['notes']) ? $_POST['notes'] : '';
    $id_service = isset($_POST['id_service']) ? $_POST['id_service'] : 0;
    
    for ($i = 0; $i < $_POST['countDisplay']; $i++){
        $service_name = $_POST['service_name'];
        // $cariId_service = mysqli_query($koneksi, "SELECT id FROM services WHERE service_name = '$service_name'");
        // $rowid_service = mysqli_fetch_assoc($cariId_service);
        // $id_service = $rowid_service['id'];
    
        $instOrderDet = mysqli_query($koneksi, "INSERT INTO order_detail(id_order, id_service, `qty`, `notes`) VALUES ('$id_order', '$id_service', '$qty[$i]', '$notes[$i]')");
    }
    header('location: ?page=trans-order&add=success');
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
                                <input type="number" name="countDisplay" id="countDisplay" value="<?=$_SESSION['click_count']?>" readonly>
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