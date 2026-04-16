<?php

session_start();

if(isset($_SESSION["login"])) {
    header("Location: index.php");
    exit;
}

require 'functions.php';

if( isset($_POST["register"]) ) {
    if( registrasi($_POST) > 0 ) {
        echo "<script>
                alert('Registrasi berhasil!'); 
                document.location.href = 'login.php';
              </script>";
    } else {
        echo "<script>
                alert('Registrasi gagal!'); 
                document.location.href = 'registrasi.php';
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
    <title>Halaman Registrasi</title>
</head>
<body>
    
    <div class="auth-page">
         <div class="login-container">
            <h2>Buat Akun</h2>
            <form action="" method="post">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input required type="text" name="username" id="username" placeholder="Masukkan Username">
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input required class="password" type="password" name="password" id="password" placeholder="Masukkan Password">
                    <button type="button" class="toggle-pw" onclick="togglePw(this)">
                        <img src="img/visibility.png">
                    </button>
                </div>
                <div class="form-group">
                    <label for="konfirmasi_password">Konfirmasi password</label>
                    <input required class="password" type="password" name="konfirmasi_password" id="konfirmasi_password" placeholder="Konfirmasi password">
                    <button type="button" class="toggle-pw" onclick="togglePw(this)">
                        <img src="img/visibility.png">
                    </button>
                </div>
                <div class="form-group">
                    <label for="konfirmasi_admin">Konfirmasi password Admin</label>
                    <input required class="password" type="password" name="konfirmasi_admin" id="konfirmasi_admin" placeholder="Konfirmasi admin">
                    <button type="button" class="toggle-pw" onclick="togglePw(this)">
                        <img src="img/visibility.png">
                    </button>
                </div>
                <button class="btn-login" type="submit" name="register">Registrasi</button>
                <div class="divider"><span>atau</span></div>
                <p class="register-text">Sudah punya akun? <a href="login.php">Login disini</a></p>
            </form>
        </div>   
    </div>

    <!-- JS -->
    <script src="js/script.js"></script>
</body>
</html>