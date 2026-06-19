<?php

session_start();

require 'functions.php';

if(!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}

updateExpired();

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$per_page = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $per_page;

$where = '';
if(!empty($search)) {
    $search_esc = mysqli_real_escape_string($conn, $search);
    $where = "WHERE tipe_studio LIKE '%$search_esc%'";
}

$total_data = query("SELECT COUNT(*) as total FROM studio $where")[0]['total'];
$total_page = ceil($total_data / $per_page);

$daftar_studio = query("SELECT * FROM studio $where ORDER BY harga ASC LIMIT $per_page OFFSET $offset");

$currentPage = basename($_SERVER['PHP_SELF']);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Kelola Studio</title>
</head>
<body class="bg-slate-100 min-h-screen">

    <!-- NAVBAR HEADER -->
    <?php include 'templates/navheader.php'; ?>

    <!-- TOAST -->
    <?php include 'templates/toast.php'; ?>

    <div class="max-w-7xl mx-auto px-6 py-8">
        <!-- HEADER -->
        <div class="flex items-center justify-between mb-6 gap-4 flex-wrap">
            <h2 class="text-xl font-bold text-slate-800">Kelola Studio</h2>
            <div class="flex items-center gap-3 flex-wrap">
                <form action="" method="get" class="flex items-center gap-2">
                    <input type="text" name="search" id="search-input" placeholder="Cari nama studio..."
                           value="<?= htmlspecialchars($search); ?>"
                           class="border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <button type="submit"
                            class="border border-blue-600 text-blue-600 hover:bg-blue-600 hover:text-white text-sm font-medium px-4 py-2 rounded-lg transition">
                        Cari
                    </button>
                    <?php if(!empty($search)) : ?>
                        <a href="studio.php"
                           class="bg-slate-200 hover:bg-slate-300 text-slate-600 text-sm font-medium px-4 py-2 rounded-lg transition">
                            Reset
                        </a>
                    <?php endif; ?>
                </form>
                <a href="tambahstudio.php"
                   class="border border-blue-600 text-blue-600 hover:bg-blue-600 hover:text-white text-sm font-medium px-4 py-2 rounded-lg transition">
                    + Tambah Studio
                </a>
            </div>
        </div>
        
        <!-- TABEL -->
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-slate-700">
                    <thead class="bg-slate-50 text-slate-500 uppercase text-xs">
                        <tr>
                            <th class="px-4 py-3 text-left">#</th>
                            <th class="px-4 py-3 text-left">Foto</th>
                            <th class="px-4 py-3 text-left">Nama Studio</th>
                            <th class="px-4 py-3 text-left">Harga/Jam</th>
                            <th class="px-4 py-3 text-left">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php $no = 1 + $offset; ?>
                        <?php foreach($daftar_studio as $studio) : ?>
                        <tr class="border-b border-slate-100 hover:bg-slate-50 transition">
                            <td class="px-4 py-3"><?= $no ?></td>
                            <td class="px-4 py-3">
                                <img src="img/studio/<?= $studio['foto']; ?>"
                                     alt="<?= $studio['tipe_studio']; ?>"
                                     class="w-20 h-14 object-cover rounded-lg">
                            </td>
                            <td class="px-4 py-3 font-medium"><?= $studio["tipe_studio"]; ?></td>
                            <td class="px-4 py-3">Rp. <?= number_format($studio["harga"], 0, ',', '.'); ?>/Jam</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <form action="editstudio.php" method="post">
                                        <input type="hidden" name="id" value="<?= $studio['id']; ?>">
                                        <button type="submit" class="text-xs border border-amber-400 text-amber-500 hover:bg-amber-400 hover:text-white px-3 py-1 rounded-lg transition">
                                            Edit
                                        </button>
                                    </form>
                                <button type="button"
                                        onclick="bukaModal('<?= $studio['id'] ?>', '', 'hapusstudio.php', 'Hapus Studio', 'Yakin ingin menghapus studio ini? Data tidak bisa dikembalikan.', 'Hapus', 'border border-red-400 text-red-400 hover:bg-red-400 hover:text-white')"
                                        class="text-xs border border-red-400 text-red-400 hover:bg-red-400 hover:text-white px-3 py-1 rounded-lg transition">
                                    Hapus
                                </button>
                                </div>
                            </td>
                        </tr>
                        <?php $no++; ?>
                        <?php endforeach; ?>
                        <?php if(empty($daftar_studio)) : ?>
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-sm text-slate-400">
                                    <?= !empty($search) ? "Studio \"" . htmlspecialchars($search) . "\" tidak ditemukan." : "Belum ada data studio." ?>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- PAGINATION -->
        <?php if($total_page > 1) : ?>
            <div class="flex items-center justify-center gap-2 mt-6">
                <?php if($page > 1) : ?>
                    <a href="?page=<?= $page-1 ?>&search=<?= urlencode($search) ?>"
                    class="px-3 py-1.5 text-sm bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition">
                        &laquo; Prev
                    </a>
                <?php endif; ?>

                <?php for($i = 1; $i <= $total_page; $i++) : ?>
                    <a href="?page=<?= $i ?>&search=<?= urlencode($search) ?>"
                    class="px-3 py-1.5 text-sm rounded-lg border transition
                            <?= $i == $page ? 'bg-blue-600 text-white border-blue-600' : 'bg-white border-slate-300 hover:bg-slate-50' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>

                <?php if($page < $total_page) : ?>
                    <a href="?page=<?= $page+1 ?>&search=<?= urlencode($search) ?>"
                    class="px-3 py-1.5 text-sm bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition">
                        Next &raquo;
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>

    </div>

    <!-- MODAL HAPUS -->
    <div id="modal-hapus" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
        <div id="modal-panel" class="bg-white rounded-2xl shadow-lg p-6 w-full max-w-sm mx-4">
            <h3 id="modal-title" class="text-base font-semibold text-slate-800 mb-2"></h3>
            <p id="modal-desc" class="text-sm text-slate-500 mb-6"></p>
            <div class="flex justify-end gap-3">
                <button onclick="tutupModal()"
                        class="border border-slate-300 text-slate-600 hover:bg-slate-100 text-sm font-medium px-4 py-2 rounded-lg transition">
                    Batal
                </button>
                <form id="modal-form" action="" method="post">
                    <input type="hidden" name="id" id="modal-id">
                    <input type="hidden" name="status" id="modal-status">
                    <input type="hidden" name="page" id="modal-page" value="<?= $page ?>">
                    <button id="modal-btn" type="submit"
                            class="text-sm font-medium px-4 py-2 rounded-lg transition">
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- JS -->
    <script src="js/script.js"></script>
</body>
</html>