<?php
session_start();
ob_start();

require_once 'koneksi.php'; //Koneksi Database

// $user_id = $_SESSION['user']['id'];

// Query untuk mengambil data user
// $query = "SELECT name,email FROM users WHERE id = '$user_id'";

// Eksekusi query
// $result = mysqli_query($koneksi, $query);

// Jika user ditemukan, ambil data dan simpan di sesi
// if (mysqli_num_rows($result) > 0) {
//     $user = mysqli_fetch_assoc($result);
//     $_SESSION['user']['name'] = $user['name'];
//     $_SESSION['user']['email'] = $user['email'];
// }

?>

<?php require_once "template/header.php"; ?>
<?php require_once "template/sidebar.php"; ?>

<main id="main" class="main">

    <?php
    if (isset($_GET['page'])) {
        if (file_exists('content/' . $_GET['page'] . ".php")) {
            include 'content/' . $_GET['page'] . ".php";
        } else {
            include "content/home.php";
        }
    } else {
        include "content/home.php";
    }
    ?>

</main><!-- End #main -->

<?php require_once "template/footer.php"; ?>