<?php

session_start();

require 'functions.php';

if(isset($_SESSION["login"])) {
    header("Location: index.php");
    exit;
}

if( isset($_POST["login"]) ) {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $result = mysqli_query($conn, "SELECT * FROM admin WHERE username = '$username'");

    if( mysqli_num_rows($result) === 1 ) {
        $row = mysqli_fetch_assoc($result);
        if( password_verify($password, $row["password"]) ) {
            $_SESSION["login"] = true;
            $_SESSION["username"] = $row["username"];
            $_SESSION["success"] = 'Login Berhasil!';
            header("Location: index.php");
            exit;
        }
    }

    echo "<script>
            alert('username atau password salah');
	        document.location.href='login.php';
        </script>";
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
            <h2>Login Akun</h2>
            <form action="" method="post">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input required type="text" name="username" id="username" placeholder="Masukkan Username">
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input required class="password" type="password" name="password" id="password" placeholder="Masukkan Password">
                    <button type="button" class="toggle-pw" onclick="togglePw(this)">
                        <img id="eye-icon" src="img/visibility.png">
                    </button>
                </div>
                <button class="btn-login" type="submit" name="login">Masuk</button>
                <div class="divider"><span>atau</span></div>
                <p class="register-text">Belum punya akun? <a href="registrasi.php">Daftar disini</a></p>
            </form>
        </div>   
    </div>

    <!-- JS -->
    <script src="js/script.js"></script>
</body>
</html>