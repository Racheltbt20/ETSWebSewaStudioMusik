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

$id = $_POST["id"];

$booking = query("SELECT t.*, s.tipe_studio 
                  FROM transaksi t 
                  JOIN studio s ON t.studio_id = s.id 
                  WHERE t.id = $id")[0];
$durasi = (strtotime($booking["jam_selesai"]) - strtotime($booking["jam_mulai"])) / 3600;

if(isset($_POST['submit'])) {
    if( bayar($_POST) > 0 ) {
        echo "<script>
                alert('Pembayaran Berhasil!'); 
                document.location.href = 'daftarbooking.php';
              </script>";
    } else {
        echo "<script>
                alert('Pembayaran gagal!'); 
                document.location.href = 'bayar.php?id=$id'
              </script>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Bayar Sewa Studio</title>
</head>
<body>

    <!-- NAVBAR HEADER -->
    <?php include 'templates/navheader.php'; ?>

    <!-- FORM PEMBAYARAN -->
    <div class="booking-section">
        <a href="daftarbooking.php" class="btn-booking" style="display:inline-block; margin-bottom:20px;">
            Kembali
        </a>
        <div class="booking-form-container">
            <form action="" method="post" class="booking-form">
                <div class="booking-col">
                    <input type="hidden" name="id" value="<?= $booking['id']; ?>">
                    <div class="form-group">
                        <label for="nama">Nama</label>
                        <input type="text" name="nama" id="nama" value="<?= $booking["nama"]; ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="studio_id">Tipe Studio</label>
                        <input type="text" name="studio_id" id="studio_id" value="<?= $booking["tipe_studio"]; ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="durasi">Durasi</label>
                        <div class="input-group">
                            <input type="number" name="durasi" id="durasi" value="<?= $durasi; ?>" readonly>
                            <span>Jam</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="tanggal">Tanggal</label>
                        <input type="text" name="tanggal" id="tanggal" value="<?= date('d-m-Y', strtotime($booking['tanggal'])); ?>" readonly>
                    </div>
                </div>
                <div class="booking-col">
                    <div class="form-group">
                        <label for="total_harga">Total harga</label>
                        <div class="input-group input-group-left">
                            <span>Rp.</span>
                            <input type="text" name="total_harga" id="total_harga" value="<?= $booking["total_harga"]; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="total_bayar">Total bayar</label>
                        <div class="input-group input-group-left">
                            <span>Rp.</span>
                            <input type="number" name="total_bayar" id="total_bayar" min="<?= $booking["total_harga"]; ?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="kembalian">Kembalian: </label>
                        <div class="input-group input-group-left">
                            <span>Rp.</span>
                            <input type="number" name="kembalian" id="kembalian" readonly>
                        </div>
                    </div>
                    <div class="form-action">
                        <button type="reset" class="btn-action batal">Reset</button>
                        <button type="submit" name="submit" class="btn-action selesai" style="width:auto; padding:8px 20px;">
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