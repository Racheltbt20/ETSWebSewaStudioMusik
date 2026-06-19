<?php if(isset($_SESSION["success"])) : ?>
    <div id="toast" class="fixed top-4 right-4 z-50 flex items-center gap-3 bg-green-50 border border-green-200 text-green-700 text-sm rounded-lg px-4 py-3 shadow-md transition-opacity duration-500">
        <span><?= $_SESSION["success"]; ?></span>
        <button onclick="document.getElementById('toast').remove()" class="text-green-500 hover:text-green-700 font-bold text-base leading-none">&times;</button>
    </div>
    <script>
        setTimeout(() => {
            const toast = document.getElementById('toast');
            if(toast) {
                toast.style.opacity = '0';
                setTimeout(() => toast.remove(), 500);
            }
        }, 3000);
    </script>
    <?php unset($_SESSION["success"]); ?>
<?php endif; ?>

<?php if(isset($_SESSION["error"])) : ?>
    <div id="toast-error" class="fixed top-4 right-4 z-50 flex items-center gap-3 bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg px-4 py-3 shadow-md transition-opacity duration-500">
        <span><?= $_SESSION["error"]; ?></span>
        <button onclick="document.getElementById('toast-error').remove()" class="text-red-500 hover:text-red-700 font-bold text-base leading-none">&times;</button>
    </div>
    <script>
        setTimeout(() => {
            const toast = document.getElementById('toast-error');
            if(toast) {
                toast.style.opacity = '0';
                setTimeout(() => toast.remove(), 500);
            }
        }, 3000);
    </script>
    <?php unset($_SESSION["error"]); ?>
<?php endif; ?>