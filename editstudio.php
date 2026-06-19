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
$studio = query("SELECT * FROM studio WHERE id = $id")[0];

$errors = [];
$old = [];

if(isset($_POST["submit"])) {
    $old = $_POST;
    $result = editStudio($_POST, $_FILES);
    if(is_array($result) && !empty($result)) {
        $errors = $result;
    } else if($result > 0) {
        $_SESSION["success"] = "Studio berhasil diubah!";
        header("Location: studio.php");
        exit;
    } else if($result === 0) {
        $_SESSION["success"] = "Tidak ada perubahan data.";
        header("Location: studio.php");
        exit;
    }
}

$currentPage = basename($_SERVER['PHP_SELF']);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Edit Studio</title>
</head>
<body class="bg-slate-100 min-h-screen">

    <!-- NAVBAR HEADER -->
    <?php include 'templates/navheader.php'; ?>

    <div class="max-w-4xl mx-auto px-6 py-8">
        <a href="studio.php"
           class="inline-block mb-6 text-sm border border-slate-300 text-slate-600 hover:bg-slate-200 px-4 py-2 rounded-lg transition">
            &larr; Kembali
        </a>
        <!-- FORM -->
        <div class="bg-white rounded-2xl shadow-sm p-8">
            <h2 class="text-xl font-bold text-slate-800 mb-6">Edit Studio</h2>
            <form action="" id="studio-form" method="post" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <input type="hidden" name="id" value="<?= $studio['id']; ?>">
                <input type="hidden" name="foto_lama" value="<?= $studio['foto']; ?>">
                <div class="flex flex-col gap-4">
                    <div class="flex flex-col gap-1">
                        <label for="tipe_studio" class="text-sm font-medium text-slate-600">Nama Studio</label>
                        <input type="text" name="tipe_studio" id="tipe_studio"
                               value="<?= htmlspecialchars(!empty($old['tipe_studio']) ? $old['tipe_studio'] : $studio['tipe_studio']) ?>"
                               class="border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2
                                      <?= isset($errors['tipe_studio']) ? 'border-red-400 focus:ring-red-400' : 'border-slate-300 focus:ring-blue-500' ?>">
                        <?php if(isset($errors['tipe_studio'])) : ?>
                            <p class="text-xs text-red-500"><?= $errors['tipe_studio'] ?></p>
                        <?php endif ?>
                    </div>
                    <div class="flex flex-col gap-1">
                        <label for="harga" class="text-sm font-medium text-slate-600">Harga per Jam (Rp)</label>
                        <div class="flex items-center border rounded-lg overflow-hidden focus-within:ring-2
                                    <?= isset($errors['harga']) ? 'border-red-400 focus-within:ring-red-400' : 'border-slate-300 focus-within:ring-blue-500' ?>">
                            <span class="text-sm text-slate-500 bg-slate-50 border-r border-slate-300 px-3 py-2 shrink-0">Rp</span>
                            <input type="number" name="harga" id="harga" min="1"
                                   value="<?= !empty($old['harga']) ? $old['harga'] : $studio['harga'] ?>"
                                   class="px-3 py-2 text-sm focus:outline-none w-full">
                        </div>
                        <?php if(isset($errors['harga'])) : ?>
                            <p class="text-xs text-red-500"><?= $errors['harga'] ?></p>
                        <?php endif ?>
                    </div>
                </div>
                <div class="flex flex-col gap-4">
                    <div class="flex flex-col gap-1">
                        <label class="text-sm font-medium text-slate-600">Foto Saat Ini</label>
                        <img id="preview"
                             src="img/studio/<?= $studio['foto']; ?>"
                             alt="<?= $studio['tipe_studio']; ?>"
                             class="w-full h-40 object-cover rounded-lg border border-slate-200">
                    </div>
                    <div class="flex flex-col gap-1">
                        <label for="foto" class="text-sm font-medium text-slate-600">Ganti Foto (opsional)</label>
                        <input type="file" name="foto" id="foto" accept=".jpg,.jpeg,.png"
                               onchange="previewFoto(this)"
                               class="border rounded-lg px-3 py-2 text-sm text-slate-500 file:mr-3 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-sm file:bg-blue-50 file:text-blue-600 hover:file:bg-blue-100 cursor-pointer
                                      <?= isset($errors['foto']) ? 'border-red-400' : 'border-slate-300' ?>">
                        <?php if(isset($errors['foto'])) : ?>
                            <p class="text-xs text-red-500"><?= $errors['foto'] ?></p>
                        <?php endif ?>
                    </div>
                    <div class="flex items-center gap-3 mt-auto">
                        <button type="reset" onclick="resetPreview('<?= $studio['foto']; ?>')"
                                class="border border-slate-400 text-slate-600 hover:bg-slate-400 hover:text-white text-sm font-medium px-5 py-2 rounded-lg transition">
                            Reset
                        </button>
                        <button type="submit" name="submit"
                                class="border border-blue-600 text-blue-600 hover:bg-blue-600 hover:text-white text-sm font-medium px-5 py-2 rounded-lg transition">
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