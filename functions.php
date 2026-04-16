<?php

$conn = mysqli_connect("localhost", "root", "", "sewa_studio");

date_default_timezone_set("Asia/Jakarta");
define('ADMIN_SECRET', 'SuperAdmin123');

function query($query) {
    global $conn;

    $result = mysqli_query($conn, $query);

    $rows = [];
    while( $row = mysqli_fetch_assoc($result) ) {
        $rows[] = $row;
    }

    return $rows;
}

function validasi($data) {
    $errors = [];
    $today = date("Y-m-d");
    $now = date("H:i");

    if(empty($data["nama"])) {
        $errors[] = "Nama wajib diisi!";
    } elseif(strlen($data["nama"]) < 2) {
        $errors[] = "Nama minimal 2 karakter!";
    }

    if(empty($data["telepon"])) {
        $errors[] = "Telepon wajib diisi!";
    } elseif(!is_numeric($data["telepon"])) {
        $errors[] = "Telepon harus angka!";
    } elseif(strlen($data["telepon"]) < 10) {
        $errors[] = "Telepon minimal 10 digit!";
    }

    if(empty($data["studio_id"])) {
        $errors[] = "Studio harus dipilih!";
    }

    if(empty($data["tanggal"])) {
        $errors[] = "Tanggal wajib diisi!";
    } elseif($data["tanggal"] < date("Y-m-d")) {
        $errors[] = "Tanggal minimal hari ini!";
    }

    if(empty($data["jam_mulai"])) {
        $errors[] = "Jam mulai wajib diisi!";
    } elseif($data["tanggal"] == $today && $data["jam_mulai"] < $now) {
        $errors[] = "Jam mulai tidak boleh kurang dari jam sekarang!";
    }

    if(empty($data["durasi"])) {
        $errors[] = "Durasi wajib diisi!";
    } elseif($data["durasi"] < 1) {
        $errors[] = "Durasi minimal 1 jam!";
    }

    return $errors;
}

function tambah($data) {
    global $conn;

    $errors = validasi($data);
    if(!empty($errors)) {
        $errorText = implode("\\n", $errors);
        echo "<script>alert('$errorText');</script>";
        return false;
    }

    $nama = htmlspecialchars($data["nama"]);
    $telepon = htmlspecialchars($data["telepon"]);

    $studio_id = $data["studio_id"];
    $studio = query("SELECT harga FROM studio WHERE id = $studio_id")[0];
    $harga = $studio["harga"];

    $tanggal = $data["tanggal"];
    $jam_mulai = $data["jam_mulai"];
    $durasi = $data["durasi"];
    $jam_selesai = date("H:i:s", strtotime("+$durasi hours", strtotime($jam_mulai)));

    $cek = query("SELECT * FROM transaksi WHERE studio_id = $studio_id AND tanggal = '$tanggal' AND status IN ('menunggu', 'dibayar')");
    foreach($cek as $b) {
        $jam_mulai_baru = strtotime($jam_mulai);
        $jam_selesai_baru = strtotime($jam_selesai);

        $jam_mulai_lama = strtotime($b["jam_mulai"]);
        $jam_selesai_lama = strtotime($b["jam_selesai"]);

        if($jam_mulai_baru < $jam_selesai_lama && $jam_selesai_baru > $jam_mulai_lama) {
            echo "<script>alert('Jadwal bentrok dengan booking lain!');</script>";
            return false;
        }
    }

    $total_harga = $durasi * $harga;

    $query = "INSERT INTO transaksi VALUES(NULL, '$nama', '$telepon', '$studio_id', '$tanggal', '$jam_mulai', '$jam_selesai', $total_harga, 'menunggu')";
    mysqli_query($conn, $query);
    
    return mysqli_affected_rows($conn);
}

function updateExpired() {
    global $conn;

    $now = date("Y-m-d H:i:s");

    $query = "UPDATE transaksi SET status = 'kedaluwarsa' WHERE status = 'menunggu' AND CONCAT(tanggal, ' ', jam_mulai) <= '$now'";
    mysqli_query($conn, $query);
}

function bayar($data) {
    global $conn;

    $id = $data["id"];
    $total_harga = $data["total_harga"];
    $total_bayar = $data["total_bayar"];

    if( $total_bayar < $total_harga ) {
        return -1;
    }

    $query = "UPDATE transaksi SET status = 'dibayar' WHERE id = $id";
    mysqli_query($conn, $query);
    return mysqli_affected_rows($conn);
}

function hapus($id) {
    global $conn;
    
    mysqli_query($conn, "DELETE FROM transaksi WHERE id = $id");

    return mysqli_affected_rows($conn);
}

function selesai($id) {
    global $conn;

    mysqli_query($conn, "UPDATE transaksi SET status = 'selesai' WHERE id = $id");

    return mysqli_affected_rows($conn);
}

function registrasi($data) {
    global $conn;
    $errors = [];

    $username = strtolower(stripslashes($data["username"]));
    $password = mysqli_real_escape_string($conn, $data["password"]);
    $konfirmasi_password = mysqli_real_escape_string($conn, $data["konfirmasi_password"]);
    $konfirmasi_admin = $data["konfirmasi_admin"];

    if(empty($username)) {
        $errors[] = "Username wajib diisi!";
    } 

    if(empty($data["password"])) {
        $errors[] = "Password wajib diisi!";
    } elseif(strlen($data["password"]) < 8) {
        $errors[] = "Password minimal 8 karakter!";
    }

    if(empty($data["konfirmasi_password"])) {
        $errors[] = "Konfirmasi password wajib diisi!";
    } elseif($password !== $konfirmasi_password) {
        $errors[] = "Konfirmasi password tidak sesuai!";
    }

    if($konfirmasi_admin !== ADMIN_SECRET) {
        echo "<script>alert('Kode admin salah!');</script>";
        return false;
    }

    if(!empty($username)) {
        $result = mysqli_query($conn, "SELECT username FROM admin WHERE username = '$username'");
        if(mysqli_fetch_assoc($result)) {
            $errors[] = "Username sudah terdaftar!";
        }
    }

    if(!empty($errors)) {
        $errorText = implode("\\n", $errors);
        echo "<script>alert('$errorText');</script>";
        return false;
    }

    $password = password_hash($password, PASSWORD_DEFAULT);

    mysqli_query($conn, "INSERT INTO admin VALUES (NULL, '$username', '$password')");

    return mysqli_affected_rows($conn);
}

?>