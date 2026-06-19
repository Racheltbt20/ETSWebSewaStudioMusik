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
        $errors["nama"] = "Nama wajib diisi!";
    } elseif(strlen($data["nama"]) < 2) {
        $errors["nama"] = "Nama minimal 2 karakter!";
    } elseif(!preg_match("/^[a-zA-Z\s]+$/", $data["nama"])) {
        $errors["nama"] = "Nama hanya boleh berisi huruf!";
    }

    if(empty($data["telepon"])) {
        $errors["telepon"] = "Telepon wajib diisi!";
    } elseif(!is_numeric($data["telepon"])) {
        $errors["telepon"] = "Telepon harus angka!";
    } elseif(strlen($data["telepon"]) < 10) {
        $errors["telepon"] = "Telepon minimal 10 digit!";
    }

    if(empty($data["studio_id"])) {
        $errors["studio_id"] = "Studio harus dipilih!";
    }

    if(empty($data["tanggal"])) {
        $errors["tanggal"] = "Tanggal wajib diisi!";
    } elseif($data["tanggal"] < date("Y-m-d")) {
        $errors["tanggal"] = "Tanggal minimal hari ini!";
    }

    if(empty($data["jam_mulai"])) {
        $errors["jam_mulai"] = "Jam mulai wajib diisi!";
    } elseif($data["tanggal"] == $today && $data["jam_mulai"] < $now) {
        $errors["jam_mulai"] = "Jam mulai tidak boleh kurang dari jam sekarang!";
    }

    if(empty($data["durasi"])) {
        $errors["durasi"] = "Durasi wajib diisi!";
    } elseif($data["durasi"] < 1) {
        $errors["durasi"] = "Durasi minimal 1 jam!";
    }

    return $errors;
}

function cekBentrok($studio_id, $tanggal, $jam_mulai, $jam_selesai, $exclude_id = null) {
    $where = "WHERE studio_id = $studio_id AND tanggal = '$tanggal' AND status IN ('menunggu', 'dibayar')";
    if($exclude_id) {
        $where .= " AND id != $exclude_id";
    }

    $cek = query("SELECT * FROM transaksi $where");
    foreach($cek as $b) {
        $mulai_baru = strtotime($jam_mulai);
        $selesai_baru = strtotime($jam_selesai);
        $mulai_lama = strtotime($b["jam_mulai"]);
        $selesai_lama = strtotime($b["jam_selesai"]);

        if($mulai_baru < $selesai_lama && $selesai_baru > $mulai_lama) {
            return true;
        }
    }
    return false;
}

function tambahBooking($data) {
    global $conn;

    $errors = validasi($data);
    if(!empty($errors)) {
        return $errors;
    }

    $nama = htmlspecialchars($data["nama"]);
    $telepon = htmlspecialchars($data["telepon"]);

    $studio_id = (int)$data["studio_id"];
    $studio = query("SELECT harga FROM studio WHERE id = $studio_id")[0];
    $harga = $studio["harga"];

    $tanggal = $data["tanggal"];
    $jam_mulai = $data["jam_mulai"];
    $durasi = $data["durasi"];
    $jam_selesai = date("H:i:s", strtotime("+$durasi hours", strtotime($jam_mulai)));

    if(cekBentrok($studio_id, $tanggal, $jam_mulai, $jam_selesai)) {
        return ["jadwal" => "Jadwal bentrok dengan booking lain!"];
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

function editBooking($data) {
    global $conn;

    $errors = validasi($data);
    if(!empty($errors)) {
        return $errors;
    }

    $id = (int)$data["id"];
    $nama = htmlspecialchars($data["nama"]);
    $telepon = htmlspecialchars($data["telepon"]);

    $studio_id = (int)$data["studio_id"];
    $studio = query("SELECT harga FROM studio WHERE id = $studio_id")[0];
    $harga = $studio["harga"];

    $tanggal = $data["tanggal"];
    $jam_mulai = $data["jam_mulai"];
    $durasi = $data["durasi"];
    $jam_selesai = date("H:i:s", strtotime("+$durasi hours", strtotime($jam_mulai)));

    if(cekBentrok($studio_id, $tanggal, $jam_mulai, $jam_selesai, $id)) {
        return ["jadwal" => "Jadwal bentrok dengan booking lain!"];
    }

    $total_harga = $durasi * $harga;

    $query = "UPDATE transaksi SET nama='$nama', telepon='$telepon', studio_id='$studio_id', tanggal='$tanggal', jam_mulai='$jam_mulai', jam_selesai='$jam_selesai', total_harga=$total_harga WHERE id=$id";
    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}

function bayar($data) {
    global $conn;

    $id = (int)$data["id"];
    $total_harga = $data["total_harga"];
    $total_bayar = $data["total_bayar"];

    if( $total_bayar < $total_harga ) {
        return -1;
    }

    $query = "UPDATE transaksi SET status = 'dibayar' WHERE id = $id";
    mysqli_query($conn, $query);
    return mysqli_affected_rows($conn);
}

function hapusBooking($id) {
    global $conn;

    $id = (int)$id;
    mysqli_query($conn, "DELETE FROM transaksi WHERE id = $id");

    return mysqli_affected_rows($conn);
}

function selesai($id) {
    global $conn;

    $id = (int)$id;
    mysqli_query($conn, "UPDATE transaksi SET status = 'selesai' WHERE id = $id");

    return mysqli_affected_rows($conn);
}

function tambahStudio($data, $file) {
    global $conn;
    $errors = [];

    $tipe_studio = htmlspecialchars(trim($data["tipe_studio"]));
    $harga = $data["harga"];

    if(empty($tipe_studio)) {
        $errors["tipe_studio"] = "Nama studio wajib diisi!";
    }

    if(empty($harga)) {
        $errors["harga"] = "Harga wajib diisi!";
    } elseif(!is_numeric($harga) || $harga < 1) {
        $errors["harga"] = "Harga harus berupa angka lebih dari 0!";
    }

    if(empty($errors["tipe_studio"])) {
        $cek = query("SELECT id FROM studio WHERE tipe_studio = '$tipe_studio'");
        if(!empty($cek)) {
            $errors["tipe_studio"] = "Nama studio sudah ada!";
        }
    }

    $allowed_types = ['image/jpeg', 'image/jpg', 'image/png'];
    $max_size = 5 * 1024 * 1024;

    if($file["foto"]["error"] === 0) {
        if(!in_array($file["foto"]["type"], $allowed_types)) {
            $errors["foto"] = "Tipe file tidak valid! Hanya JPG, JPEG, PNG.";
        } elseif($file["foto"]["size"] > $max_size) {
            $errors["foto"] = "Ukuran file maksimal 5MB!";
        }
    } else {
        $errors["foto"] = "Foto wajib diupload!";
    }

    if(!empty($errors)) {
        return $errors;
    }

    $ext = pathinfo($file["foto"]["name"], PATHINFO_EXTENSION);
    $nama_file = uniqid("studio_") . "." . $ext;
    $upload_path = "img/studio/" . $nama_file;

    if(!move_uploaded_file($file["foto"]["tmp_name"], $upload_path)) {
        return ["foto" => "Gagal mengupload foto!"];
    }

    $query = "INSERT INTO studio VALUES(NULL, '$tipe_studio', $harga, '$nama_file')";
    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}

function editStudio($data, $file) {
    global $conn;
    $errors = [];

    $id = (int)$data["id"];
    $tipe_studio = htmlspecialchars(trim($data["tipe_studio"]));
    $harga = $data["harga"];

    if(empty($tipe_studio)) {
        $errors["tipe_studio"] = "Nama studio wajib diisi!";
    }

    if(empty($harga)) {
        $errors["harga"] = "Harga wajib diisi!";
    } elseif(!is_numeric($harga) || $harga < 1) {
        $errors["harga"] = "Harga harus berupa angka lebih dari 0!";
    }

    if(empty($errors["tipe_studio"])) {
        $cek = query("SELECT id FROM studio WHERE tipe_studio = '$tipe_studio' AND id != $id");
        if(!empty($cek)) {
            $errors["tipe_studio"] = "Nama studio sudah ada!";
        }
    }

    $nama_file = $data["foto_lama"];

    if($file["foto"]["error"] === 0) {
        $allowed_types = ['image/jpeg', 'image/jpg', 'image/png'];
        $max_size = 5 * 1024 * 1024;

        if(!in_array($file["foto"]["type"], $allowed_types)) {
            $errors["foto"] = "Tipe file tidak valid! Hanya JPG, JPEG, PNG.";
        } elseif($file["foto"]["size"] > $max_size) {
            $errors["foto"] = "Ukuran file maksimal 5MB!";
        }

        if(empty($errors)) {
            $foto_lama_path = "img/studio/" . $data["foto_lama"];
            if(file_exists($foto_lama_path)) {
                unlink($foto_lama_path);
            }

            $ext = pathinfo($file["foto"]["name"], PATHINFO_EXTENSION);
            $nama_file = uniqid("studio_") . "." . $ext;
            $upload_path = "img/studio/" . $nama_file;

            if(!move_uploaded_file($file["foto"]["tmp_name"], $upload_path)) {
                return ["foto" => "Gagal mengupload foto!"];
            }
        }
    }

    if(!empty($errors)) {
        return $errors;
    }

    $query = "UPDATE studio SET tipe_studio='$tipe_studio', harga=$harga, foto='$nama_file' WHERE id=$id";
    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}

function hapusStudio($id) {
    global $conn;
    $id = (int)$id;

    $studio = query("SELECT foto FROM studio WHERE id = $id");
    if(!empty($studio)) {
        $foto_path = "img/studio/" . $studio[0]["foto"];
        if(file_exists($foto_path)) {
            unlink($foto_path);
        }
    }

    mysqli_query($conn, "DELETE FROM studio WHERE id = $id");

    return mysqli_affected_rows($conn);
}

function registrasi($data) {
    global $conn;
    $errors = [];

    $username = mysqli_real_escape_string($conn, strtolower(stripslashes($data["username"])));
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
        $_SESSION["error"] = "Kode admin salah!";
        return false;
    }

    if(!empty($username)) {
        $result = mysqli_query($conn, "SELECT username FROM admin WHERE username = '$username'");
        if(mysqli_fetch_assoc($result)) {
            $errors[] = "Username sudah terdaftar!";
        }
    }

    if(!empty($errors)) {
        $_SESSION["error"] = implode(" ", $errors);
        return false;
    }

    $password = password_hash($password, PASSWORD_DEFAULT);

    mysqli_query($conn, "INSERT INTO admin VALUES (NULL, '$username', '$password')");

    return mysqli_affected_rows($conn);
}

?>