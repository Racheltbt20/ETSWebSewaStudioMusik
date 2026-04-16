<?php

session_start();

require 'functions.php';

if(!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}

$tipe_studio = query("SELECT * FROM studio");

if( isset($_POST["submit"])) {
    if( tambah($_POST) > 0 ) {
        echo "<script>
                alert('Booking berhasil dibuat!'); 
                document.location.href = 'daftarbooking.php';
              </script>";
    } else {
        echo "<script>
                alert('Booking gagal dibuat!'); 
                document.location.href = 'booking.php';
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
    <title>Booking Studio</title>
</head>
<body>

    <!-- NAVBAR HEADER -->
    <?php include 'templates/navheader.php'; ?>
    
    <!-- FORM BOOKING -->
    <div class="booking-section">
        <a href="daftarbooking.php" class="btn-booking" style="display:inline-block; margin-bottom:20px;">
            Kembali
        </a>
        <div class="booking-form-container">
            <form action="" method="post" class="booking-form">
                <div class="booking-col">
                    <div class="form-group">
                        <label for="nama">Nama</label>
                        <input type="text" name="nama" id="nama" placeholder="Nama">
                    </div>
                    <div class="form-group">
                        <label for="telepon">Telepon</label>
                        <input type="text" name="telepon" id="telepon" placeholder="Nomor Telepon">
                    </div>
                    <div class="form-group">
                        <label for="studio_id">Studio</label>
                        <select name="studio_id" id="studio_id" class="input-select">
                            <option value="" disabled selected>Pilih Studio</option>
                            <?php foreach($tipe_studio as $studio) : ?>
                                <option value="<?= $studio["id"]; ?>">
                                    <?= $studio["tipe_studio"]; ?> | Rp. <?= number_format($studio["harga"], 0, ',', '.') ?>
                                </option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="tanggal">Tanggal</label>
                        <input type="date" name="tanggal" id="tanggal">
                    </div>
                </div>
                <div class="booking-col">
                    <div class="form-group">
                        <label for="jam_mulai">Jam Mulai</label>
                        <input type="time" name="jam_mulai" id="jam_mulai">
                    </div>
                    <div class="form-group">
                        <label for="durasi">Durasi</label>
                        <div class="input-group">
                            <input type="number" name="durasi" id="durasi" min="1" placeholder="Durasi">
                            <span>Jam</span>
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