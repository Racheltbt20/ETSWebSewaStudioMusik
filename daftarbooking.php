<?php

session_start();

require 'functions.php';

if(!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}

updateExpired();

$daftar_booking = query("SELECT t.*, s.tipe_studio FROM transaksi t JOIN studio s ON t.studio_id = s.id ORDER BY t.id DESC");

$currentPage = basename($_SERVER['PHP_SELF']);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Daftar Booking</title>
</head>
<body>

    <!-- NAVBAR HEADER -->
    <?php include 'templates/navheader.php'; ?>

    <!-- TABEL DAFTAR BOOKING -->
    <div class="booking-section">
        <div class="booking-header">
            <h2>Daftar Booking</h2>
            <a href="booking.php" class="btn-booking">Booking</a>
        </div>
        <div class="booking-table-wrapper">
            <table class="booking-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Telepon</th>
                        <th>Studio</th>
                        <th>Tanggal</th>
                        <th>Jam Mulai</th>
                        <th>Jam Selesai</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; ?>
                    <?php foreach($daftar_booking as $booking) : ?>
                    <tr>
                        <td><?= $no ?></td>
                        <td><?= $booking["nama"]; ?></td>
                        <td><?= $booking["telepon"]; ?></td>
                        <td><?= $booking["tipe_studio"]; ?></td>
                        <td><?= date('d-m-Y', strtotime($booking['tanggal'])); ?></td>
                        <td><?= $booking["jam_mulai"]; ?></td>
                        <td><?= $booking["jam_selesai"]; ?></td>
                        <td>Rp. <?= number_format($booking["total_harga"], 0, ',', '.'); ?></td>
                        <td>
                            <span class="status <?= $booking["status"]; ?>">
                                <?= $booking["status"]; ?>
                            </span>
                        </td>
                        <td>
                            <?php if($booking["status"] == 'menunggu') : ?>
                                <form action="bayar.php" method="post" style="display:inline;">
                                    <input type="hidden" name="id" value="<?= $booking['id']; ?>">
                                    <button type="submit" class="btn-action bayar">
                                        Bayar
                                    </button>
                                </form>
                                <form action="hapus.php" method="post" style="display:inline;">
                                    <input type="hidden" name="id" value="<?= $booking['id']; ?>">
                                    <button type="submit" class="btn-action batal" onclick="return confirm('Ingin menghapus data ini?')">
                                        Batal
                                    </button>
                                </form>
                            <?php elseif($booking["status"] == 'kedaluwarsa') : ?>
                                <form action="hapus.php" method="post" style="display:inline;">
                                    <input type="hidden" name="id" value="<?= $booking['id']; ?>">
                                    <button type="submit" class="btn-action batal" onclick="return confirm('Ingin menghapus data ini?')">
                                        Hapus
                                    </button>
                                </form>                            
                            <?php elseif($booking["status"] == 'dibayar') : ?>
                                <form action="selesai.php" method="post" style="display:inline;">
                                    <input type="hidden" name="id" value="<?= $booking['id']; ?>">
                                    <button type="submit" class="btn-action selesai" onclick="return confirm('Selesaikan booking?')">
                                        Selesai
                                    </button>
                                </form>                            
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php $no++ ?>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- JS -->
    <script src="js/script.js"></script>
</body>
</html>