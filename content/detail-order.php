<?php 

?>

<?php
include('koneksi.php');

if (isset($_GET['detail'])) {
    $id = $_GET['detail'];
    // $query = mysqli_query($koneksi, "SELECT `order`.*, `order`.id AS trId, customer.*, customer.id AS csId FROM `order` LEFT JOIN customer ON `order`.`id_customer` = customer.id WHERE `order`.id = '$id'");
    $query = mysqli_query($koneksi, "SELECT `order`.*, `order`.id AS trId, customer.*, customer.id AS csId FROM `order` 
    LEFT JOIN customer ON `order`.`id_customer` = customer.id 
    LEFT JOIN order_detail ON `order`.id = order_detail.id_order
    WHERE `order`.id = '$id'");
    $rows = mysqli_fetch_assoc($query);

}
// var_dump($rows);
// die;

$qeue = mysqli_query($koneksi, "SELECT order_detail.*, order_detail.id AS trId, services.service_name FROM order_detail LEFT JOIN `order` ON `order`.id = order_detail.id_order LEFT JOIN services ON order_detail.id_service = services.id WHERE `order`.id = '$id'");
$rowsD = mysqli_fetch_all($qeue, MYSQLI_ASSOC);

?>

<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Transaction</h5>
            </div>
            <div class="card-body">
                
                <!-- General Form Elements -->
                <form>
                    <div class="row mb-3 mt-3">
                        <label class="col-sm-2 col-form-label">Transaction Code</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" value="<?php echo $rows['trans_code'] ?>" readonly>
                        </div>
                    </div>
                    <div class="row mb-3 mt-3">
                        <label class="col-sm-2 col-form-label">Customer Name</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" value="<?php echo $rows['customer_name'] ?>" readonly>
                        </div>
                    </div>
                    <div class="row mb-3 mt-3">
                        <label class="col-sm-2 col-form-label">Address</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" value="<?php echo $rows['address'] ?>" readonly>
                        </div>
                    </div>
                    <div class="row mb-3 mt-3">
                        <label class="col-sm-2 col-form-label">Date Order</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" value="<?php echo $rows['order_date'] ?>" readonly>
                        </div>
                    </div>
                    <div class="row mb-3 mt-3">
                        <label class="col-sm-2 col-form-label">PickUp Date</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" value="<?php echo $rows['order_end_date'] ?>" readonly>
                        </div>
                    </div>

                </form><!-- End General Form Elements -->

                <hr>
                <div class="card-body">
                    <h5 class="card-title">Order</h5>
                    <!-- Active Table -->
                    <table class="table table-borderless">
                        <thead>
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Service</th>
                                <th scope="col">Qty</th>
                                <th scope="col">Price</th>
                            </tr>
                        </thead>

                        <tbody>
                        <?php $no = 1;
                        $total = 0;
                        foreach ($rowsD as $row): ?>
                            <tr>
                                <td scope="row"><?= $no++?></td>
                                <td><?= $row['service_name'] ?></td>
                                <td><?= $row['qty'] ?> Kg</td>
                                <td>Rp. <?= $row['subtotal'] ?></td>
                            </tr>
                           
                            <?php endforeach ?>
                        </tbody>
                        
                        <tfoot class="border-top">
                            <tr>
                                <th scope="col"></th>
                                <th scope="col"></th>
                                <th scope="col">Total</th>
                                <th scope="col"> Rp. <?= $rows['total'] ?></th>
                            </tr>
                        </tfoot>

                    </table>
                    <!-- End Tables without borders -->
                    <button class="btn btn-dark btn-sm" onclick="window.open('../cetak.php?detail=<?= $rows['trId'] ?>', '_blank')">Cetak Struk</button>

                    <!-- <button class="btn btn-dark btn-sm" onclick="window.open('../cetak.php', '_blank')">Cetak Struk</button> -->

                </div>

            </div>
        </div>
    </div>
</div>