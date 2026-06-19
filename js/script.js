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
    // DROPDOWN
    const dropdownToggle = document.getElementById('userDropdown');
    const icon = document.getElementById('dropdownIcon');

    if(dropdownToggle) {
        const dropdownMenu = document.querySelector('.dropdown-menu');

        dropdownToggle.addEventListener('click', function(e) {
            e.preventDefault();
            dropdownMenu.classList.toggle('hidden');
            const isOpen = !dropdownMenu.classList.contains('hidden');
            icon.src = isOpen ? "img/arrow-up.png" : "img/arrow-down.png";
            this.setAttribute('aria-expanded', isOpen);
        });

        document.addEventListener('click', function(e) {
            if (!dropdownToggle.contains(e.target) && !dropdownMenu.contains(e.target)) {
                dropdownMenu.classList.add('hidden');
                icon.src = "img/arrow-down.png";
            }
        });
    }

    // SEARCH FOCUS
    const searchInputFocus = document.getElementById('search-input');
    if(searchInputFocus) {
        searchInputFocus.focus();
        const val = searchInputFocus.value;
        searchInputFocus.value = '';
        searchInputFocus.value = val;
    }

    // MODAL KLIK LUAR AREA
    const modal = document.getElementById('modal-hapus');
    if(modal) {
        modal.addEventListener('click', function(e) {
            if(e.target === this) tutupModal();
        });
    }
});

const totalBayarInput = document.getElementById("total_bayar");
if (totalBayarInput) {
    totalBayarInput.addEventListener('input', () => {
        let total = document.getElementById('total_harga').value;
        let bayar = totalBayarInput.value;
        document.getElementById('kembalian').value = bayar - total;
    });
}

function previewFoto(input) {
    const preview = document.getElementById('preview');
    if(input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function resetPreview(fotoLama) {
    const preview = document.getElementById('preview');
    if(fotoLama) {
        preview.src = 'img/studio/' + fotoLama;
    } else {
        preview.src = '#';
        preview.style.display = 'none';
    }
}

const bookingForm = document.getElementById('booking-form');
if(bookingForm) {
    const resetBtn = bookingForm.querySelector('button[type="reset"]');
    if(resetBtn) {
        resetBtn.addEventListener('click', function(e) {
            e.preventDefault();
            bookingForm.querySelectorAll('input').forEach(input => {
                if(input.type !== 'hidden') input.value = '';
            });
            bookingForm.querySelectorAll('select').forEach(select => select.selectedIndex = 0);
        });
    }
}

const studioForm = document.getElementById('studio-form');
if(studioForm) {
    const resetBtn = studioForm.querySelector('button[type="reset"]');
    if(resetBtn) {
        resetBtn.addEventListener('click', function(e) {
            e.preventDefault();
            studioForm.querySelectorAll('input').forEach(input => {
                if(input.type !== 'hidden') input.value = '';
            });
            resetPreview();
        });
    }
}

const searchInput = document.getElementById('search-input');
if(searchInput) {
    let timeout;
    searchInput.addEventListener('input', function() {
        clearTimeout(timeout);
        timeout = setTimeout(() => {
            this.closest('form').submit();
        }, 500);
    });
}

function bukaModal(id, status, action, title, desc, btnText, btnClass) {
    document.getElementById('modal-id').value = id;
    document.getElementById('modal-status').value = status;
    document.getElementById('modal-form').action = action;
    document.getElementById('modal-title').textContent = title;
    document.getElementById('modal-desc').textContent = desc;
    document.getElementById('modal-btn').textContent = btnText;
    document.getElementById('modal-btn').className = 'text-sm font-medium px-4 py-2 rounded-lg transition ' + btnClass;
    
    const modal = document.getElementById('modal-hapus');
    const panel = document.getElementById('modal-panel');
    
    modal.classList.remove('hidden');
    modal.style.opacity = '0';
    panel.style.transform = 'scale(0.9)';
    panel.style.opacity = '0';
    
    requestAnimationFrame(() => {
        modal.style.transition = 'opacity 0.2s ease';
        panel.style.transition = 'transform 0.2s ease, opacity 0.2s ease';
        modal.style.opacity = '1';
        panel.style.transform = 'scale(1)';
        panel.style.opacity = '1';
    });
}

function tutupModal() {
    const modal = document.getElementById('modal-hapus');
    const panel = document.getElementById('modal-panel');
    
    modal.style.opacity = '0';
    panel.style.transform = 'scale(0.9)';
    panel.style.opacity = '0';
    
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 200);
}