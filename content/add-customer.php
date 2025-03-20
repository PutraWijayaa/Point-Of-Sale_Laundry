<?php

date_default_timezone_set('Asia/Jakarta');
$date = new DateTime('now', new DateTimeZone('Asia/Jakarta'));

//fungsi untuk menyimpan data 
if (isset($_POST['save'])) {
    $customer_name = $_POST['customer_name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    $insert = mysqli_query($koneksi, "INSERT INTO customer (customer_name,phone,address) VALUES ('$customer_name','$phone','$address')");

    if ($insert) {
        header("location: ?page=customer&add=success");
    } else {
        header("location: ?page=home");
    }
}
// end Fungsi tambah data 


// fungsi edit data 
$id = isset($_GET['edit']) ? $_GET['edit'] : '';
$queryEdit = mysqli_query($koneksi, "SELECT * FROM customer WHERE id = '$id' ");
$rowEdit = mysqli_fetch_assoc($queryEdit);

if (isset($_POST['edit'])) {
    $id = $_GET['edit'];
    $customer_name = $_POST['customer_name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    $queryEdt = mysqli_query($koneksi, "UPDATE customer SET customer_name = '$customer_name', phone = '$phone', address = '$address' WHERE id = '$id'");

    if ($queryEdt) {
        header("location: ?page=customer&update=success");
    }
}
// End fungsi edit 

?>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h3 class="text-center">Add Data Customer</h3>
            </div>

            <div class="card-body">

                <form class="row g-3 mt-3" method="post" enctype="multipart/form-data">
                    <div class="col-md-12">
                        <div class="form-floating">
                            <input value="<?php echo isset($_GET['edit']) ? $rowEdit['customer_name'] : '' ?>" type="text" class="form-control" id="floatingName" placeholder="Full Name" name="customer_name">
                            <label for="floatingName">Customer Name</label>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-floating">
                            <input value="<?php echo isset($_GET['edit']) ? $rowEdit['phone'] : '' ?>" type="text" class="form-control" id="floatingEmail" placeholder="Your Phone Number" name="phone">
                            <label for="floatingEmail">Phone Number</label>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-floating">
                            <textarea class="form-control" placeholder="Address" id="floatingTextarea" style="height: 100px;" name="address"><?php echo isset($_GET['edit']) ? $rowEdit['address'] : '' ?></textarea>
                            <label for="floatingTextarea">Address</label>
                        </div>
                    </div>

                    <!-- <div class="col-md-12">
                        <div class="form-floating">
                            <input type="date" class="form-control" name="created_at" value="<?php echo $date->format('Y-m-d H:i:s') ?>">
                            <label for="floatingEmail">Created At</label>
                        </div>
                    </div> -->

                    <div class="text-center">
                        <button type="submit" name="<?php echo isset($_GET['edit']) ? 'edit' : 'save' ?>" class="btn btn-dark">Submit</button>
                        <button type="reset" class="btn btn-danger">Reset</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>