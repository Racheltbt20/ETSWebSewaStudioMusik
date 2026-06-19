<?php

session_start();

require 'functions.php';

if(!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: daftarbooking.php");
    exit;
}

$id = (int)$_POST["id"];
$page = isset($_POST['page']) ? (int)$_POST['page'] : 1;

$booking = query("SELECT t.*, s.tipe_studio 
                  FROM transaksi t 
                  JOIN studio s ON t.studio_id = s.id 
                  WHERE t.id = $id")[0];
$durasi = (strtotime($booking["jam_selesai"]) - strtotime($booking["jam_mulai"])) / 3600;

if(isset($_POST['submit'])) {
    if( bayar($_POST) > 0 ) {
        $_SESSION["success"] = "Pembayaran berhasil!";
        header("Location: daftarbooking.php?page=$page");
        exit;
    } else {
        $_SESSION["error"] = "Jumlah bayar kurang dari total harga!";
        header("Location: daftarbooking.php");
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Pembayaran</title>
</head>
<body class="bg-slate-100 min-h-screen">

    <!-- NAVBAR HEADER -->
    <?php include 'templates/navheader.php'; ?>

    <div class="max-w-4xl mx-auto px-6 py-8">
        <a href="daftarbooking.php"
           class="inline-block mb-6 text-sm border border-slate-300 text-slate-600 hover:bg-slate-200 px-4 py-2 rounded-lg transition">
            &larr; Kembali
        </a>
        <!-- FORM -->
        <div class="bg-white rounded-2xl shadow-sm p-8">
            <h2 class="text-xl font-bold text-slate-800 mb-6">Pembayaran</h2>
            <form action="" method="post" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <input type="hidden" name="page" value="<?= $page ?>">
                <input type="hidden" name="id" value="<?= $booking['id']; ?>">
                <div class="flex flex-col gap-4">
                    <div class="flex flex-col gap-1">
                        <label class="text-sm font-medium text-slate-600">Nama</label>
                        <input type="text" value="<?= $booking['nama']; ?>" readonly
                               class="border border-slate-200 bg-slate-50 rounded-lg px-3 py-2 text-sm text-slate-500 cursor-not-allowed">
                    </div>
                    <div class="flex flex-col gap-1">
                        <label class="text-sm font-medium text-slate-600">Tipe Studio</label>
                        <input type="text" value="<?= $booking['tipe_studio']; ?>" readonly
                               class="border border-slate-200 bg-slate-50 rounded-lg px-3 py-2 text-sm text-slate-500 cursor-not-allowed">
                    </div>
                    <div class="flex flex-col gap-1">
                        <label class="text-sm font-medium text-slate-600">Durasi</label>
                        <div class="flex items-center border border-slate-200 bg-slate-50 rounded-lg overflow-hidden">
                            <input type="number" value="<?= $durasi; ?>" readonly
                                   class="px-3 py-2 text-sm text-slate-500 bg-slate-50 focus:outline-none w-full cursor-not-allowed">
                            <span class="text-sm text-slate-400 bg-slate-100 border-l border-slate-200 px-3 py-2 shrink-0">Jam</span>
                        </div>
                    </div>
                    <div class="flex flex-col gap-1">
                        <label class="text-sm font-medium text-slate-600">Tanggal</label>
                        <input type="text" value="<?= date('d-m-Y', strtotime($booking['tanggal'])); ?>" readonly
                               class="border border-slate-200 bg-slate-50 rounded-lg px-3 py-2 text-sm text-slate-500 cursor-not-allowed">
                    </div>
                </div>
                <div class="flex flex-col gap-4">
                    <div class="flex flex-col gap-1">
                        <label class="text-sm font-medium text-slate-600">Total Harga</label>
                        <div class="flex items-center border border-slate-200 bg-slate-50 rounded-lg overflow-hidden">
                            <span class="text-sm text-slate-400 bg-slate-100 border-r border-slate-200 px-3 py-2 shrink-0">Rp</span>
                            <input type="text" name="total_harga" id="total_harga" value="<?= $booking['total_harga']; ?>" readonly
                                   class="px-3 py-2 text-sm text-slate-500 bg-slate-50 focus:outline-none w-full cursor-not-allowed">
                        </div>
                    </div>
                    <div class="flex flex-col gap-1">
                        <label class="text-sm font-medium text-slate-600">Total Bayar</label>
                        <div class="flex items-center border border-slate-300 rounded-lg overflow-hidden focus-within:ring-2 focus-within:ring-blue-500">
                            <span class="text-sm text-slate-500 bg-slate-50 border-r border-slate-300 px-3 py-2 shrink-0">Rp</span>
                            <input type="number" name="total_bayar" id="total_bayar" min="<?= $booking['total_harga']; ?>" required
                                   class="px-3 py-2 text-sm focus:outline-none w-full">
                        </div>
                    </div>
                    <div class="flex flex-col gap-1">
                        <label class="text-sm font-medium text-slate-600">Kembalian</label>
                        <div class="flex items-center border border-slate-200 bg-slate-50 rounded-lg overflow-hidden">
                            <span class="text-sm text-slate-400 bg-slate-100 border-r border-slate-200 px-3 py-2 shrink-0">Rp</span>
                            <input type="number" name="kembalian" id="kembalian" readonly
                                   class="px-3 py-2 text-sm text-slate-500 bg-slate-50 focus:outline-none w-full cursor-not-allowed">
                        </div>
                    </div>
                    <div class="flex items-center gap-3 mt-auto">
                        <button type="reset"
                                class="border border-slate-400 text-slate-600 hover:bg-slate-400 hover:text-white text-sm font-medium px-5 py-2 rounded-lg transition">
                            Reset
                        </button>
                        <button type="submit" name="submit"
                                class="border border-blue-600 text-blue-600 hover:bg-blue-600 hover:text-white text-sm font-medium px-5 py-2 rounded-lg transition">
                            Simpan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <!-- JS -->
    <script src="js/script.js"></script>
</body>
</html>