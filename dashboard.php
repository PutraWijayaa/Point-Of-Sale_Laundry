<?php
session_start();
ob_start();

require_once 'koneksi.php';
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