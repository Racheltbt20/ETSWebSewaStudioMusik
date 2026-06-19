<?php $currentPage = $currentPage ?? basename($_SERVER['PHP_SELF']); ?>

<header class="bg-white shadow-sm sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-6 py-3 flex items-center justify-between">
        <nav class="flex items-center gap-6">
            <a href="index.php" 
               class="text-sm font-medium transition <?= $currentPage == 'index.php' ? 'text-blue-600 border-b-2 border-blue-600 pb-0.5' : 'text-slate-600 hover:text-blue-600' ?>">
                Home
            </a>
            <a href="daftarbooking.php" 
               class="text-sm font-medium transition <?= $currentPage == 'daftarbooking.php' ? 'text-blue-600 border-b-2 border-blue-600 pb-0.5' : 'text-slate-600 hover:text-blue-600' ?>">
                Daftar Booking
            </a>
            <a href="studio.php" 
               class="text-sm font-medium transition <?= $currentPage == 'studio.php' ? 'text-blue-600 border-b-2 border-blue-600 pb-0.5' : 'text-slate-600 hover:text-blue-600' ?>">
                Studio
            </a>
        </nav>

        <div class="relative">
            <button id="userDropdown" aria-haspopup="true" aria-expanded="false"
                    class="flex items-center text-sm font-medium text-slate-600 hover:text-blue-600 transition">
                <?= $_SESSION["username"] ?? "User"; ?>
                <img id="dropdownIcon" src="img/arrow-down.png" class="w-4 h-4 opacity-80">
            </button>
            <ul class="dropdown-menu absolute right-0 mt-2 w-36 bg-white border border-slate-200 rounded-lg shadow-lg hidden">
                <li>
                    <a href="logout.php" class="block px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 hover:text-red-500 rounded-lg transition">
                        Logout
                    </a>
                </li>
            </ul>
        </div>
    </div>
</header>