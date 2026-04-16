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

if(selesai($id) > 0) {
    echo "<script>
            alert('Status diubah menjadi selesai!'); 
            document.location.href = 'daftarbooking.php';
          </script>";
} else {
    echo "<script>
            alert('Status gagal diubah!'); 
            document.location.href = 'daftarbooking.php';
         </script>";
}

?>