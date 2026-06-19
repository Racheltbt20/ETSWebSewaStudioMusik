<?php

session_start();

if(isset($_SESSION["login"])) {
    header("Location: index.php");
    exit;
}

require 'functions.php';

if(isset($_POST["register"])) {
    $result = registrasi($_POST);
    if($result > 0) {
        $_SESSION["success"] = "Registrasi berhasil!";
        header("Location: login.php");
        exit;
    } else {
        $_SESSION["error"] = "Registrasi gagal!";
        header("Location: registrasi.php");
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
    <title>Registrasi</title>
</head>
<body class="bg-slate-100 min-h-screen flex items-center justify-center">

    <?php include 'templates/toast.php'; ?>

    <div class="bg-white rounded-2xl shadow-md w-full max-w-sm p-8">
        <h2 class="text-2xl font-bold text-slate-800 mb-6 text-center">Buat Akun</h2>
        <form action="" method="post" class="flex flex-col gap-4">
            <div class="flex flex-col gap-1">
                <label for="username" class="text-sm font-medium text-slate-600">Username</label>
                <input required type="text" name="username" id="username" placeholder="Masukkan Username"
                       class="border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="flex flex-col gap-1">
                <label for="password" class="text-sm font-medium text-slate-600">Password</label>
                <div class="relative">
                    <input required type="password" name="password" id="password" placeholder="Masukkan Password"
                           class="password border border-slate-300 rounded-lg px-3 py-2 text-sm w-full focus:outline-none focus:ring-2 focus:ring-blue-500 pr-10">
                    <button type="button" class="toggle-pw absolute right-2 top-1/2 -translate-y-1/2" onclick="togglePw(this)">
                        <img src="img/visibility.png" class="w-5 h-5 opacity-50">
                    </button>
                </div>
            </div>
            <div class="flex flex-col gap-1">
                <label for="konfirmasi_password" class="text-sm font-medium text-slate-600">Konfirmasi Password</label>
                <div class="relative">
                    <input required type="password" name="konfirmasi_password" id="konfirmasi_password" placeholder="Konfirmasi password"
                           class="password border border-slate-300 rounded-lg px-3 py-2 text-sm w-full focus:outline-none focus:ring-2 focus:ring-blue-500 pr-10">
                    <button type="button" class="toggle-pw absolute right-2 top-1/2 -translate-y-1/2" onclick="togglePw(this)">
                        <img src="img/visibility.png" class="w-5 h-5 opacity-50">
                    </button>
                </div>
            </div>
            <div class="flex flex-col gap-1">
                <label for="konfirmasi_admin" class="text-sm font-medium text-slate-600">Konfirmasi Password Admin</label>
                <div class="relative">
                    <input required type="password" name="konfirmasi_admin" id="konfirmasi_admin" placeholder="Konfirmasi admin"
                           class="password border border-slate-300 rounded-lg px-3 py-2 text-sm w-full focus:outline-none focus:ring-2 focus:ring-blue-500 pr-10">
                    <button type="button" class="toggle-pw absolute right-2 top-1/2 -translate-y-1/2" onclick="togglePw(this)">
                        <img src="img/visibility.png" class="w-5 h-5 opacity-50">
                    </button>
                </div>
            </div>
            <button type="submit" name="register"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg py-2 text-sm transition">
                Registrasi
            </button>
            <div class="flex items-center gap-2 text-slate-400 text-xs">
                <div class="flex-1 h-px bg-slate-200"></div>
                <span>atau</span>
                <div class="flex-1 h-px bg-slate-200"></div>
            </div>
            <p class="text-center text-sm text-slate-500">
                Sudah punya akun? <a href="login.php" class="text-blue-600 hover:underline font-medium">Login disini</a>
            </p>
        </form>
    </div>

    <!-- JS -->
    <script src="js/script.js"></script>
</body>
</html>