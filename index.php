<?php

session_start();

require 'functions.php';

if(!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}

updateExpired();

$currentPage = basename($_SERVER['PHP_SELF']);

$studios = query("SELECT * FROM studio ORDER BY harga ASC");
$total_booking = query("SELECT COUNT(*) as total FROM transaksi WHERE status IN ('menunggu', 'dibayar', 'selesai')")[0]['total'];
$total_studio = query("SELECT COUNT(*) as total FROM studio")[0]['total'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Home</title>
</head>
<body class="bg-slate-100 min-h-screen">
        
    <!-- NAVBAR -->
    <?php include 'templates/navheader.php'; ?>

    <!-- TOAST -->
    <?php include 'templates/toast.php'; ?>

    <!-- KARTU STATISTIK -->
    <div class="max-w-7xl mx-auto px-6 pt-8 pb-0">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-0">
            <div class="bg-white rounded-2xl shadow-sm p-6 flex items-center gap-4">
                <div>
                    <p class="text-sm text-slate-500 mb-1">Total Booking</p>
                    <p class="text-3xl font-bold text-slate-800"><?= $total_booking ?></p>
                    <p class="text-xs text-slate-400 mt-1">Menunggu, dibayar & selesai</p>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-sm p-6 flex items-center gap-4">
                <div>
                    <p class="text-sm text-slate-500 mb-1">Total Studio</p>
                    <p class="text-3xl font-bold text-slate-800"><?= $total_studio ?></p>
                    <p class="text-xs text-slate-400 mt-1">Studio tersedia</p>
                </div>
            </div>
        </div>
    </div>

    <!-- DAFTAR STUDIO -->
    <div class="max-w-7xl mx-auto px-6 py-8">
        <h2 class="text-xl font-bold text-slate-800 mb-6">Daftar Studio</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php foreach($studios as $studio) : ?>
                <div class="bg-white rounded-2xl shadow-sm overflow-hidden hover:shadow-md transition">
                    <img src="img/studio/<?= $studio['foto']; ?>" 
                         alt="<?= $studio['tipe_studio']; ?>"
                         class="w-full h-40 object-cover">
                    <div class="p-4">
                        <div class="font-semibold text-slate-800"><?= $studio["tipe_studio"] ?></div>
                        <div class="text-blue-600 text-sm font-medium mt-1">
                            Rp. <?= number_format($studio["harga"], 0, ',', '.') ?> / JAM
                        </div>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
    </div>

    <!-- JS -->
    <script src="js/script.js"></script>
</body>
</html>