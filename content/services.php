<?php

$queryCustomer = mysqli_query($koneksi, "SELECT * FROM services ORDER BY id DESC");
$rowCustomer = mysqli_fetch_all($queryCustomer, MYSQLI_ASSOC);


if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $delete = mysqli_query($koneksi, "DELETE FROM services WHERE id = '$id'");
    header("location:?page=services&notif=success");
}

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
                                <a href="?page=services&delete=<?php echo $row['id'] ?>" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Are You Sure?')"><i class="bi bi-trash3-fill"></i></a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>