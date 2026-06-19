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
$status = $_POST["status"];
$page = isset($_POST['page']) ? (int)$_POST['page'] : 1;

if(hapusBooking($id) > 0) {
    if($status === 'menunggu') {
        $_SESSION["success"] = "Booking berhasil dibatalkan!";
    } else {
        $_SESSION["success"] = "Booking berhasil dihapus!";
    }
} else {
    if($status === 'menunggu') {
        $_SESSION["error"] = "Booking gagal dibatalkan!";
    } else {
        $_SESSION["error"] = "Booking gagal dihapus!";
    }
}

header("Location: daftarbooking.php?page=$page");
exit;

?>