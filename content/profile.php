<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['ID_USER'])) {
    header("Location: index.php");
    exit();
}

require_once 'koneksi.php';

// Ambil ID user dari session
$id_user = $_SESSION['ID_USER'];

// Ambil data user
$stmt = $koneksi->prepare("SELECT name, email FROM user WHERE id = ?");
$stmt->bind_param("i", $id_user);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Jika user tidak ditemukan
if (!$user) {
    echo "User tidak ditemukan.";
    exit();
}

// Proses update nama
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_name"])) {
    $new_name = trim($_POST["name"]);

    if (!empty($new_name)) {
        $stmt = $koneksi->prepare("UPDATE user SET name = ? WHERE id = ?");
        $stmt->bind_param("si", $new_name, $id_user);
        if ($stmt->execute()) {
            echo "<script>alert('Nama berhasil diperbarui!'); window.location.href='?page=profile';</script>";
        } else {
            echo "<script>alert('Gagal memperbarui nama.');</script>";
        }
    }
}

// Proses update password
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_password"])) {
    $current_password = $_POST["current_password"];
    $new_password = $_POST["new_password"];
    $confirm_password = $_POST["confirm_password"];

    // Ambil password hash dari database
    $stmt = $koneksi->prepare("SELECT password FROM user WHERE id = ?");
    $stmt->bind_param("i", $id_user);
    $stmt->execute();
    $result = $stmt->get_result();
    $user_data = $result->fetch_assoc();

    if (!password_verify($current_password, $user_data["password"])) {
        echo "<script>alert('Password lama salah!');</script>";
    } elseif ($new_password !== $confirm_password) {
        echo "<script>alert('Konfirmasi password tidak cocok!');</script>";
    } else {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $koneksi->prepare("UPDATE user SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $hashed_password, $id_user);
        if ($stmt->execute()) {
            echo "<script>alert('Password berhasil diperbarui!'); window.location.href='?page=profile';</script>";
        } else {
            echo "<script>alert('Gagal memperbarui password.');</script>";
        }
    }
}
?>


    <div class="pagetitle">
      <h1>Profile</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item">Users</li>
          <li class="breadcrumb-item active">Profile</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section profile">
      <div class="row">
        <div class="col-xl-4">

          <div class="card">
            <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">

              <img src="assets/img/profile-img.jpg" alt="Profile" class="rounded-circle">
              <h2><?php echo htmlspecialchars($user['name']); ?></h2>
              <h3><?php echo htmlspecialchars($user['email']); ?></h3>
             
            </div>
          </div>

        </div>

        <div class="col-xl-8">

          <div class="card">
            <div class="card-body pt-3">
              <!-- Bordered Tabs -->
              <ul class="nav nav-tabs nav-tabs-bordered" role="tablist">


                <li class="nav-item" role="presentation">
                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit" aria-selected="false" role="tab" tabindex="-1">Edit Profile</button>
                </li>

                <li class="nav-item" role="presentation">
                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-settings" aria-selected="false" role="tab" tabindex="-1">Update Name</button>
                </li>

                <li class="nav-item" role="presentation">
                  <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-change-password" aria-selected="true" role="tab">Change Password</button>
                </li>

              </ul>
              <div class="tab-content pt-2">

                <div class="tab-pane fade profile-edit pt-3" id="profile-edit" role="tabpanel">

                  <!-- Profile Edit Form -->
                  <form>
                    <div class="row mb-3">
                      <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Full Name</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="fullName" type="text" class="form-control" id="fullName" value="<?php echo htmlspecialchars($user['name']); ?>">
                      </div>
                    </div>
                    <div class="row mb-3">
                      <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Email</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="fullName" type="text" class="form-control" id="fullName" value="<?php echo htmlspecialchars($user['email']); ?>">
                      </div>
                    </div>
                  </form><!-- End Profile Edit Form -->

                </div>

                <div class="tab-pane fade pt-3" id="profile-settings" role="tabpanel">

                  <!-- Settings Form -->
                  <form method="POST" action="">
                        <div class="mb-3">
                            <label for="name" class="form-label">Update Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                        </div>
                        <button type="submit" name="update_name" class="btn btn-success">Update Name</button>
                    </form>

                </div>

                <div class="tab-pane fade pt-3 active show" id="profile-change-password" role="tabpanel">
                  <!-- Change Password Form -->
                  <form method="POST" action="">
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Current Password</label>
                            <input type="password" class="form-control" id="current_password" name="current_password" required>
                        </div>
                        <div class="mb-3">
                            <label for="new_password" class="form-label">New Password</label>
                            <input type="password" class="form-control" id="new_password" name="new_password" required>
                        </div>
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                        </div>
                        <button type="submit" name="update_password" class="btn btn-warning">Update Password</button>
                    </form>

                </div>

              </div><!-- End Bordered Tabs -->

            </div>
          </div>

        </div>
      </div>
    </section>

  
