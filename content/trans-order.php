<?php
include('koneksi.php');

$query = mysqli_query(
    $koneksi,
    "SELECT `order`.*, customer.customer_name FROM `order` LEFT JOIN customer ON customer.id = `order`.id_customer WHERE `order`.deleted_at = 0 ORDER BY `order`.id DESC;"
);

$rows = mysqli_fetch_all($query, MYSQLI_ASSOC);

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $delete = mysqli_query($koneksi, "UPDATE order SET deleted_at = 1 WHERE id = '$id'");
    header("location: ?page=trans-order&notif=success");
}

?>
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <h3>Data Trans Order</h3>
            </div>
            <div class="card-body">
                <div align="right" class="mb-3 mt-3">
                    <a href="?page=add-trans-order" class="btn btn-dark"><i class="bi bi-plus-circle"></i> New Order</a>
                </div>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Trans Code</th>
                            <th>Customer Name</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1;
                        foreach ($rows as $row): ?>
                        <tr>
                            <td><?php echo $no++ ?></td>
                            <td><?php echo $row['trans_code'] ?></td>
                            <td><?php echo $row['customer_name'] ?></td>
                            <td><?php echo $row['order_status'] ?></td>
                            <td class="btn-group">
                                <a href="?page=add-service&detail=<?php echo $row['id'] ?>"
                                    class="btn btn-dark btn-sm"><i class="bi bi-pencil-square"></i></a>
                                <a href="?page=service&delete=<?php echo $row['id'] ?>"
                                    onclick="return confirm('Are you sure??')" class="btn btn-danger btn-sm"><i class="bi bi-trash3-fill"></i></a>
                            </td>
                        </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>