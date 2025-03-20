<?php

$queryCustomer = mysqli_query($koneksi, "SELECT * FROM customer ORDER BY id DESC");
$rowCustomer = mysqli_fetch_all($queryCustomer, MYSQLI_ASSOC);

// hapus
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $queryDel = mysqli_query($koneksi, "DELETE FROM customer WHERE id = $id");
    header("location: ?page=customer&notif=success");
}

?>

<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <h3 class="text-center">- Customer Data -</h3>
            </div>

            <div class="card-body">
                <div align="right" class="mb-3 mt-3">
                    <a href="?page=add-customer" class="btn btn-dark"><i class="bi bi-plus-circle"></i> New Customer</a>
                </div>
                <table class="table datatable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Phone</th>
                            <th>Alamat</th>
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
                                <td><?= $row['customer_name'] ?></td>
                                <td><?= $row['phone'] ?></td>
                                <td><?= $row['address'] ?></td>
                                <td hidden><?= $row['created_at'] ?></td>
                                <td hidden><?= $row['updated_at'] ?></td>
                                <td hidden><?= $row['deleted_at'] ?></td>
                                <td class="btn-group">
                                    <a href="?page=add-customer&edit=<?php echo $row['id'] ?>" class="btn btn-dark btn-sm"><i class="bi bi-pencil-square"></i></a>
                                    <a href="?page=customer&delete=<?php echo $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are You Sure?')"><i class="bi bi-trash3-fill"></i></a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>

                <!-- <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Phone</th>
                            <th>Alamat</th>
                            <th>#</th>
                        </tr>
                    <tbody>
                        <?php
                        $no = 1;
                        foreach ($rowCustomer as $row) { ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $row['customer_name'] ?></td>
                                <td><?= $row['phone'] ?></td>
                                <td><?= $row['address'] ?></td>
                                <td>
                                    <a href="?page=add_customer$edit=<?php echo $row['id'] ?>" class="btn btn-dark btn-sm">Edit</a>
                                    <a href="" class="btn btn-danger btn-sm" onclick="return confirm('Are You Sure?')">Hapus</a>
                                </td>
                            </tr>
                        <?php } ?>

                    </tbody>

                    </thead>
                </table> -->
            </div>
        </div>
    </div>
</div>