<?php

session_start();

require 'functions.php';

if(!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}

updateExpired();

$currentPage = basename($_SERVER['PHP_SELF']);

$studios = query("SELECT * FROM studio");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>index</title>
</head>
<body>
    <!-- ALERT -->
    <?php if(isset($_SESSION["success"])): ?>
        <script>
            alert("<?= $_SESSION['success']; ?>");
        </script>
        <?php unset($_SESSION["success"]); ?>
    <?php endif; ?>

    <!-- NAVBAR HEADER -->
    <?php include 'templates/navheader.php'; ?>

    <!-- DAFTAR HARGA -->
    <div class="studio-section">
        <div class="studio-section_header">
            Daftar Studio
        </div>
        <div class="studio-grid">
            <?php foreach($studios as $studio) : ?>
                <div class="studio-card">
                    <div class="studio-card_title"><?= $studio["tipe_studio"] ?></div>
                    <div class="studio-card_price">Rp. <?= number_format($studio["harga"], 0, ',', '.') ?> / JAM</div>
                </div>
            <?php endforeach ?>
        </div>
    </div>

    <!-- JS -->
    <script src="js/script.js"></script>
</body>
</html>