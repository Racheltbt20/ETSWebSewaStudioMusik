<?php

session_start();

require 'functions.php';

if(!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: studio.php");
    exit;
}

$id = (int)$_POST["id"];
$page = isset($_POST['page']) ? (int)$_POST['page'] : 1;

if(hapusStudio($id) > 0) {
    $_SESSION["success"] = "Studio berhasil dihapus!";
} else {
    $_SESSION["error"] = "Studio gagal dihapus!";
}

header("Location: studio.php?page=$page");
exit;

?>