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

$id = isset($_POST["id"]) ? (int)$_POST["id"] : 0;
$booking_data = query("SELECT * FROM transaksi WHERE id = $id");
if(empty($booking_data)) {
    header("Location: daftarbooking.php");
    exit;
}
$booking = $booking_data[0];

$tipe_studio = query("SELECT * FROM studio ORDER BY harga ASC");

$errors = [];
$old = [];
$jadwal_error = '';

if(isset($_POST["submit"])) {
    $old = $_POST;
    $result = editBooking($_POST);
    if(is_array($result) && !empty($result)) {
        $errors = $result;
        if(isset($errors["jadwal"])) {
            $jadwal_error = $errors["jadwal"];
            unset($errors["jadwal"]);
        }
    } else if($result > 0) {
        $_SESSION["success"] = "Booking berhasil diubah!";
        header("Location: daftarbooking.php");
        exit;
    } else if($result === 0) {
        $_SESSION["success"] = "Tidak ada perubahan data.";
        header("Location: daftarbooking.php");
        exit;
    }
}

$currentPage = basename($_SERVER['PHP_SELF']);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Edit Booking</title>
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
            <h2 class="text-xl font-bold text-slate-800 mb-6">Edit Booking</h2>
            <form action="" method="post" id="booking-form" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <input type="hidden" name="id" value="<?= $booking['id']; ?>">
                <div class="flex flex-col gap-4">
                    <div class="flex flex-col gap-1">
                        <label for="nama" class="text-sm font-medium text-slate-600">Nama</label>
                        <input type="text" name="nama" id="nama"
                               value="<?= htmlspecialchars(!empty($old['nama']) ? $old['nama'] : $booking['nama']) ?>"
                               class="border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2
                                      <?= isset($errors['nama']) ? 'border-red-400 focus:ring-red-400' : 'border-slate-300 focus:ring-blue-500' ?>">
                        <?php if(isset($errors['nama'])) : ?>
                            <p class="text-xs text-red-500"><?= $errors['nama'] ?></p>
                        <?php endif ?>
                    </div>
                    <div class="flex flex-col gap-1">
                        <label for="telepon" class="text-sm font-medium text-slate-600">Telepon</label>
                        <input type="text" name="telepon" id="telepon"
                               value="<?= htmlspecialchars(!empty($old['telepon']) ? $old['telepon'] : $booking['telepon']) ?>"
                               class="border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2
                                      <?= isset($errors['telepon']) ? 'border-red-400 focus:ring-red-400' : 'border-slate-300 focus:ring-blue-500' ?>">
                        <?php if(isset($errors['telepon'])) : ?>
                            <p class="text-xs text-red-500"><?= $errors['telepon'] ?></p>
                        <?php endif ?>
                    </div>
                    <div class="flex flex-col gap-1">
                        <label for="studio_id" class="text-sm font-medium text-slate-600">Studio</label>
                        <div class="relative">
                            <select name="studio_id" id="studio_id"
                                    class="border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 appearance-none bg-white cursor-pointer w-full pr-8
                                           <?= isset($errors['studio_id']) ? 'border-red-400 focus:ring-red-400' : 'border-slate-300 focus:ring-blue-500' ?>">
                                <?php foreach($tipe_studio as $studio) : ?>
                                    <option value="<?= $studio['id']; ?>"
                                        <?= (isset($old['studio_id']) ? $old['studio_id'] == $studio['id'] : $booking['studio_id'] == $studio['id']) ? 'selected' : '' ?>>
                                        <?= $studio['tipe_studio']; ?> | Rp. <?= number_format($studio['harga'], 0, ',', '.') ?>
                                    </option>
                                <?php endforeach ?>
                            </select>
                            <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2">
                                <img src="img/arrow-down.png" alt="" class="w-4 h-4 opacity-60">
                            </span>
                        </div>
                        <?php if(isset($errors['studio_id'])) : ?>
                            <p class="text-xs text-red-500"><?= $errors['studio_id'] ?></p>
                        <?php endif ?>
                    </div>
                    <div class="flex flex-col gap-1">
                        <label for="tanggal" class="text-sm font-medium text-slate-600">Tanggal</label>
                        <input type="date" name="tanggal" id="tanggal"
                               value="<?= !empty($old['tanggal']) ? $old['tanggal'] : $booking['tanggal'] ?>"
                               class="border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2
                                      <?= isset($errors['tanggal']) ? 'border-red-400 focus:ring-red-400' : 'border-slate-300 focus:ring-blue-500' ?>">
                        <?php if(isset($errors['tanggal'])) : ?>
                            <p class="text-xs text-red-500"><?= $errors['tanggal'] ?></p>
                        <?php endif ?>
                    </div>
                </div>
                <div class="flex flex-col gap-4">
                    <div class="flex flex-col gap-1">
                        <label for="jam_mulai" class="text-sm font-medium text-slate-600">Jam Mulai</label>
                        <input type="time" name="jam_mulai" id="jam_mulai"
                               value="<?= !empty($old['jam_mulai']) ? $old['jam_mulai'] : substr($booking['jam_mulai'], 0, 5) ?>"
                               class="border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2
                                      <?= isset($errors['jam_mulai']) ? 'border-red-400 focus:ring-red-400' : 'border-slate-300 focus:ring-blue-500' ?>">
                        <?php if(isset($errors['jam_mulai'])) : ?>
                            <p class="text-xs text-red-500"><?= $errors['jam_mulai'] ?></p>
                        <?php endif ?>
                    </div>
                    <div class="flex flex-col gap-1">
                        <label for="durasi" class="text-sm font-medium text-slate-600">Durasi</label>
                        <div class="flex items-center border rounded-lg overflow-hidden focus-within:ring-2
                                    <?= isset($errors['durasi']) ? 'border-red-400 focus-within:ring-red-400' : 'border-slate-300 focus-within:ring-blue-500' ?>">
                            <input type="number" name="durasi" id="durasi" min="1"
                                   value="<?= !empty($old['durasi']) ? $old['durasi'] : (strtotime($booking['jam_selesai']) - strtotime($booking['jam_mulai'])) / 3600 ?>"
                                   class="px-3 py-2 text-sm focus:outline-none w-full">
                            <span class="text-sm text-slate-500 bg-slate-50 border-l border-slate-300 px-3 py-2 shrink-0">Jam</span>
                        </div>
                        <?php if(isset($errors['durasi'])) : ?>
                            <p class="text-xs text-red-500"><?= $errors['durasi'] ?></p>
                        <?php endif ?>
                        <?php if(!empty($jadwal_error)) : ?>
                            <p class="text-xs text-red-500"><?= $jadwal_error ?></p>
                        <?php endif ?>
                    </div>
                    <div class="flex items-center gap-3 mt-auto pt-4">
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