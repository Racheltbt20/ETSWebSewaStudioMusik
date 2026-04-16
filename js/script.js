function togglePw(btn) {
    const input = btn.previousElementSibling; 
    const icon = btn.querySelector('img');

    if (input.type === 'password') {
        input.type = 'text';
        icon.src = "img/visibility_off.png";
    } else {
        input.type = 'password';
        icon.src = "img/visibility.png";
    }
}

document.addEventListener('DOMContentLoaded', function () {
    const dropdownToggle = document.getElementById('userDropdown');
    const icon = document.getElementById('dropdownIcon');

    if (!dropdownToggle) return;
    dropdownToggle.addEventListener('click', function(e) {
        e.preventDefault();

        const parent = this.parentElement;
        parent.classList.toggle('is-active');

        const isOpen = parent.classList.contains('is-active');

        icon.src = isOpen 
            ? "img/arrow-up.png" 
            : "img/arrow-down.png";

        this.setAttribute('aria-expanded', isOpen);
    });

    document.addEventListener('click', function(e) {
        const dropdown = document.querySelector('.dropdown');

        if (dropdown && !dropdown.contains(e.target)) {
            dropdown.classList.remove('is-active');
            icon.src = "img/arrow-down.png";
        }
    });
});

const totalBayarInput = document.getElementById("total_bayar");
if (totalBayarInput) {
    totalBayarInput.addEventListener('input', () => {
        let total = document.getElementById('total_harga').value;
        let bayar = totalBayarInput.value;
        document.getElementById('kembalian').value = bayar - total;
    });
}