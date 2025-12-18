/**
 * Filename: admin/js/main.js
 * Last Update: 2025-12-17
 * Rules: 5, 6, 7, 12, 13
 */

function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('-translate-x-full');
    document.getElementById('overlay').classList.toggle('hidden');
}

function openModal(id) {
    const modal = document.getElementById(id);
    if (modal) {
        modal.classList.remove('hidden');
        if (id === 'saveModal') {
            document.getElementById('modalTitle').innerText = 'New Entry';
            document.getElementById('cat_id').value = '';
            document.getElementById('cat_name').value = '';
        }
    }
}

function closeModal(id) {
    const modal = document.getElementById(id);
    if (modal) modal.classList.add('hidden');
}

function editItem(id, name) {
    openModal('saveModal');
    document.getElementById('modalTitle').innerText = 'Edit Category';
    document.getElementById('cat_id').value = id;
    document.getElementById('cat_name').value = name;
}

function confirmDelete(id, page) {
    document.getElementById('deleteModal').classList.remove('hidden');
    document.getElementById('delConfirm').href = page + '?delete_id=' + id;
}

// Global Live Search functionality
const searchInput = document.getElementById('liveSearch');
if (searchInput) {
    searchInput.addEventListener('input', function(e) {
        const v = e.target.value.toLowerCase();
        document.querySelectorAll('table tbody tr').forEach(r => {
            r.style.display = r.innerText.toLowerCase().includes(v) ? '' : 'none';
        });
    });
}

// Global System Clock logic
const clockElement = document.getElementById('headerClock');
if (clockElement) {
    setInterval(() => {
        clockElement.innerText = new Date().toLocaleTimeString('en-GB', {
            timeZone: 'Asia/Kolkata', 
            hour12: false
        });
    }, 1000);
}