<?php $currentPage = $currentPage ?? basename($_SERVER['PHP_SELF']); ?>

<header class="site-header">
    <div class="main-navbar header-container">
        <nav>
            <ul class="main-navbar_list">
                <li class="main-navbar_item">
                    <a href="index.php" class="main-navbar_link <?= $currentPage == 'index.php' ? 'active' : '' ?>">Home</a>
                </li>
                <li class="main-navbar_item">
                    <a href="daftarbooking.php" class="main-navbar_link <?= $currentPage == 'daftarbooking.php' ? 'active' : '' ?>">Daftar Booking</a>
                </li>
            </ul>
        </nav>
        <nav>
            <ul class="main-navbar_list">
                <li class="main-navbar_item dropdown">
                    <a href="#" class="main-navbar_link" role="button" id="userDropdown" aria-haspopup="true" aria-expanded="false">
                        <?= $_SESSION["username"] ?? "User"; ?>
                        <img id="dropdownIcon" src="img/arrow-down.png" class="dropdown-icon">
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="logout.php">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
    </div>
</header>