<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['ID_USER'])) {
    header("Location: index.php");
    exit();
}

include('koneksi.php');

$queryAkses = mysqli_query($koneksi, "SELECT * FROM level ORDER BY id DESC");
$rowAkses = mysqli_fetch_all($queryAkses, MYSQLI_ASSOC);
?>

<div class="pagetitle">
    <h1>Control Akses</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="?pages">Home</a></li>
            <li class="breadcrumb-item">Pages</li>
            <li class="breadcrumb-item">Control Akses</li>
        </ol>
    </nav>
</div>

<section class="section">
  <div class="row">
    <div class="col-lg-12">

      <div class="card">
        <div class="card-body pt-4">
          <div class="d-flex justify-content-between align-items-center mb-4">
            <!-- <h5 class="card-title">Control Akses</h5> -->
            <a href="?page=add-level" class="btn btn-dark btn-sm">
              <i class="bi bi-plus-circle me-1"></i> Tambah Level
            </a>
          </div>

          <!-- Table with stripped rows -->
          <table class="table table-striped datatable">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Nama Level</th>
                <th scope="col" hidden>Created</th>
                <th scope="col" hidden>Updated</th>
                <th scope="col" hidden>Deleted</th>
                <th scope="col">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php $no = 1;
              foreach ($rowAkses as $row): ?>
              <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($row['level_name']) ?></td>
                <td hidden><?= $row['created_at'] ?></td>
                <td hidden><?= $row['updated_at'] ?></td>
                <td hidden><?= $row['deleted_at'] ?></td>
                <td>
                  <div class="btn-group btn-group-sm" role="group">
                    <a href="?page=add-level&edit=<?= $row['id'] ?>" class="btn btn-dark">
                      <i class="bi bi-pencil-square"></i>
                    </a>
                    <button type="button" class="btn btn-danger btn-delete" data-id="<?= $row['id'] ?>">
                      <i class="bi bi-trash"></i>
                    </button>
                  </div>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
          <!-- End Table with stripped rows -->

        </div>
      </div>

    </div>
  </div>
</section>

<!-- SweetAlert Delete Script -->
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const deleteButtons = document.querySelectorAll('.btn-delete');

    deleteButtons.forEach(button => {
      button.addEventListener('click', function () {
        const id = this.getAttribute('data-id');

        Swal.fire({
          title: 'Yakin ingin menghapus?',
          text: "Data akan dihapus secara permanen!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#6c757d',
          confirmButtonText: 'Ya, hapus!',
          cancelButtonText: 'Batal'
        }).then((result) => {
          if (result.isConfirmed) {
            window.location.href = '?page=akses&delete=' + id;
          }
        });
      });
    });
  });
</script>

<?php
// Handle delete setelah konfirmasi
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $queryDel = mysqli_query($koneksi, "DELETE FROM level WHERE id = $id");
    echo "<script>
        Swal.fire({
            title: 'Berhasil!',
            text: 'Data berhasil dihapus.',
            icon: 'success',
            timer: 2000,
            showConfirmButton: false
        }).then(() => {
            window.location.href = '?page=akses';
        });
    </script>";
    exit();
}
?>
