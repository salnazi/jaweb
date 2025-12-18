<?php
/**
 * Filename: admin/categories.php
 * Last Update: 2025-12-18
 * Logic: Header Integration + Button Realignment (Menus Style)
 */

require_once '../db_config.php';
require_once 'auth_check.php';
require_once 'functions.php';

$current_module = basename($_SERVER['PHP_SELF']);
$table_categories = TABLE_PREFIX . 'categories';

$limit = 4;
$page = isset($_GET['p']) ? (int)$_GET['p'] : 1;
$offset = ($page - 1) * $limit;

// --- AJAX SEARCH ---
if (isset($_GET['search_query'])) {
    $q = mysqli_real_escape_string($conn, $_GET['search_query']);
    $query = "SELECT * FROM $table_categories WHERE name LIKE '%$q%' OR slug LIKE '%$q%' ORDER BY id DESC";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $encData = base64_encode(json_encode($row));
            echo '<tr class="hover:bg-slate-800/50 transition-colors text-sm border-b border-slate-800/50">';
            echo '<td class="px-6 py-4 font-mono text-sky-500 font-bold">#' . $row['id'] . '</td>';
            echo '<td class="px-6 py-4 font-semibold text-slate-200">' . htmlspecialchars($row['name']) . '</td>';
            echo '<td class="px-6 py-4 text-slate-500 font-mono text-xs">' . htmlspecialchars($row['slug']) . '</td>';
            echo '<td class="px-6 py-4 text-right"><div class="flex justify-end gap-2">';
            echo '<button onclick="editItem(\'' . $encData . '\')" class="w-8 h-8 rounded bg-blue-600 text-white hover:bg-blue-500 transition-all"><i class="fa-solid fa-pen-to-square text-xs"></i></button>';
            echo '<button onclick="confirmDeleteWithTitle(' . $row['id'] . ',\'' . htmlspecialchars($row['name'], ENT_QUOTES) . '\',\'categories.php\')" class="w-8 h-8 rounded bg-red-600 text-white hover:bg-red-500 transition-all"><i class="fa-solid fa-trash-can text-xs"></i></button>';
            echo '</div></td></tr>';
        }
    } else {
        echo '<tr><td colspan="4" class="p-10 text-center text-slate-500 italic text-xs uppercase tracking-widest">No matching categories found</td></tr>';
    }
    exit();
}

// --- DELETE ---
if (isset($_GET['delete_id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['delete_id']);
    mysqli_query($conn, "DELETE FROM $table_categories WHERE id = '$id'");
    header("Location: categories.php"); exit();
}

// --- SAVE / UPDATE ---
if (isset($_POST['save_category'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $slug = mysqli_real_escape_string($conn, $_POST['slug']);
    if (empty($slug)) { $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name))); }
    if (!empty($id)) { $sql = "UPDATE $table_categories SET name='$name', slug='$slug' WHERE id='$id'"; }
    else { $sql = "INSERT INTO $table_categories (name, slug) VALUES ('$name', '$slug')"; }
    mysqli_query($conn, $sql); header("Location: categories.php"); exit();
}

$total_res = mysqli_query($conn, "SELECT COUNT(id) AS total FROM $table_categories");
$total_rows = mysqli_fetch_assoc($total_res)['total'];
$total_pages = ceil($total_rows / $limit);
$categories = mysqli_query($conn, "SELECT * FROM $table_categories ORDER BY id DESC LIMIT $offset, $limit");

include 'header.php';
?>

<div class="flex-1 overflow-y-auto p-6 lg:p-8">
    <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-black text-white uppercase tracking-tighter">Categories</h2>
            <p class="text-slate-500 text-[10px] font-bold uppercase tracking-widest mt-1">Config: <?= TABLE_PREFIX ?>categories</p>
        </div>
        <div class="flex items-center gap-2">
            <div class="flex items-center bg-slate-900 border border-slate-800 rounded-lg px-3 py-1 gap-3 focus-within:border-sky-500 transition-all">
                <span class="text-[10px] font-bold text-slate-500 uppercase whitespace-nowrap">Filter:</span>
                <input type="text" id="ajaxSearch" placeholder="Search..." class="bg-transparent border-none text-xs text-white outline-none w-full md:w-64 py-1.5">
            </div>
            <button onclick="window.history.back()" class="bg-slate-900 border border-slate-800 text-slate-400 hover:text-white px-3 py-2 rounded-lg text-[10px] font-bold uppercase flex items-center gap-1.5 transition-all">
                <i class="fa-solid fa-arrow-left"></i> Back
            </button>
            <button onclick="prepareNew()" class="bg-sky-600 hover:bg-sky-500 text-white px-4 py-2 rounded-lg text-[10px] font-bold uppercase transition-all shadow-lg flex items-center gap-2">
                <i class="fa-solid fa-plus"></i> Add Category
            </button>
        </div>
    </div>

    <div class="bg-slate-900 border border-slate-800 rounded-xl overflow-hidden shadow-2xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-950 border-b border-slate-800 text-[10px] font-bold text-slate-500 uppercase tracking-widest">
                        <th class="px-6 py-4 w-20">ID</th>
                        <th class="px-6 py-4">Category Name</th>
                        <th class="px-6 py-4">Slug</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody id="categoryTableBody" class="divide-y divide-slate-800/50">
                    <?php while ($row = mysqli_fetch_assoc($categories)): $encData = base64_encode(json_encode($row)); ?>
                        <tr class="hover:bg-slate-800/50 transition-colors text-sm">
                            <td class="px-6 py-4 font-mono text-sky-500 font-bold">#<?= $row['id'] ?></td>
                            <td class="px-6 py-4 font-semibold text-slate-200"><?= htmlspecialchars($row['name']) ?></td>
                            <td class="px-6 py-4 text-slate-500 font-mono text-xs"><?= htmlspecialchars($row['slug']) ?></td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <button onclick="editItem('<?= $encData ?>')" class="w-8 h-8 rounded bg-blue-600 text-white hover:bg-blue-500 transition-all"><i class="fa-solid fa-pen-to-square text-xs"></i></button>
                                    <button onclick="confirmDeleteWithTitle(<?= $row['id'] ?>,'<?= htmlspecialchars($row['name'], ENT_QUOTES) ?>','categories.php')" class="w-8 h-8 rounded bg-red-600 text-white hover:bg-red-500 transition-all"><i class="fa-solid fa-trash-can text-xs"></i></button>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <div class="p-4 bg-slate-950/50 border-t border-slate-800 flex justify-end">
            <div class="flex gap-1" id="paginationNav">
                <?php if ($page > 1): ?><a href="?p=<?= $page-1 ?>" class="px-3 py-1 bg-slate-800 hover:bg-sky-600 text-white text-xs rounded transition-colors">Prev</a><?php endif; ?>
                <?php for ($i = 1; $i <= $total_pages; $i++): ?><a href="?p=<?= $i ?>" class="px-3 py-1 <?= ($page == $i) ? 'bg-sky-600 text-white' : 'bg-slate-800 text-slate-500' ?> text-xs rounded transition-colors"><?= $i ?></a><?php endfor; ?>
                <?php if ($page < $total_pages): ?><a href="?p=<?= $page+1 ?>" class="px-3 py-1 bg-slate-800 hover:bg-sky-600 text-white text-xs rounded transition-colors">Next</a><?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div id="saveModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4 bg-black/90 backdrop-blur-sm">
    <div class="bg-slate-900 w-full max-w-md rounded-2xl border border-slate-800 shadow-2xl overflow-hidden">
        <div class="p-6 border-b border-slate-800 bg-slate-950 flex justify-between items-center"><h3 class="text-lg font-black text-white uppercase tracking-tighter" id="modalTitle">Category</h3><button onclick="closeModal('saveModal')" class="text-slate-500 hover:text-white"><i class="fa-solid fa-xmark text-xl"></i></button></div>
        <form method="POST" class="p-8 space-y-5">
            <input type="hidden" name="id" id="cat_id">
            <div><label class="block text-[10px] font-bold text-slate-400 uppercase mb-2">Category Name</label><input type="text" name="name" id="cat_name" required onkeyup="syncSlug(this.value)" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-4 py-2.5 text-white text-sm outline-none focus:border-sky-500 transition-all"></div>
            <div><label class="block text-[10px] font-bold text-slate-400 uppercase mb-2">URL Slug</label><input type="text" name="slug" id="cat_slug" required class="w-full bg-slate-950 border border-slate-800 rounded-lg px-4 py-2.5 text-sky-500 text-sm outline-none focus:border-sky-500 transition-all font-mono"><p class="text-[9px] text-slate-600 mt-2 font-bold uppercase tracking-widest">Preview: <span id="slug_preview" class="text-sky-700 lowercase">/none</span></p></div>
            <div class="flex gap-3 pt-4"><button type="submit" name="save_category" class="flex-1 bg-green-600 text-white py-3 rounded-lg font-bold text-sm uppercase transition-all shadow-lg hover:bg-green-500">Confirm Ok</button><button type="button" onclick="closeModal('saveModal')" class="flex-1 bg-red-600 text-white py-3 rounded-lg font-bold text-sm uppercase transition-all hover:bg-red-500">Cancel</button></div>
        </form>
    </div>
</div>

<div id="deleteModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4 bg-black/90 backdrop-blur-sm">
    <div class="bg-slate-900 w-full max-w-md rounded-2xl p-8 border border-slate-800 text-center shadow-2xl">
        <div class="w-16 h-16 bg-red-600/10 text-red-600 rounded-full flex items-center justify-center mx-auto mb-6 border border-red-600/20"><i class="fa-solid fa-triangle-exclamation text-2xl"></i></div>
        <h3 class="text-xl font-black text-white mb-2 uppercase tracking-tighter">Are you sure?</h3>
        <p class="text-slate-400 text-sm mb-8">Deleting category <span id="del_item_name" class="text-white font-bold underline"></span></p>
        <div class="flex gap-3"><a id="delConfirm" href="#" class="flex-1 bg-green-600 text-white py-3 rounded-lg font-bold text-sm uppercase text-center transition-all leading-[48px] hover:bg-green-500">Confirm Ok</a><button onclick="closeModal('deleteModal')" class="flex-1 bg-red-600 text-white py-3 rounded-lg font-bold text-sm uppercase transition-all hover:bg-red-500">Cancel</button></div>
    </div>
</div>

</main> <script src="js/main.js"></script>
<script>
    function syncSlug(val) {
        const slug = val.toLowerCase().trim().replace(/[^a-z0-9 -]/g, '').replace(/\s+/g, '-').replace(/-+/g, '-');
        document.getElementById('cat_slug').value = slug;
        document.getElementById('slug_preview').innerText = '/' + (slug || 'none');
    }
    document.getElementById('ajaxSearch').addEventListener('input', function() {
        fetch('categories.php?search_query=' + encodeURIComponent(this.value)).then(r => r.text()).then(html => {
            document.getElementById('categoryTableBody').innerHTML = html;
            document.getElementById('paginationNav').style.display = this.value ? 'none' : 'flex';
        });
    });
    function prepareNew() {
        document.getElementById('cat_id').value = '';
        document.getElementById('cat_name').value = '';
        document.getElementById('cat_slug').value = '';
        document.getElementById('slug_preview').innerText = '/none';
        document.getElementById('modalTitle').innerText = 'Add Category';
        openModal('saveModal');
    }
    function editItem(encoded) {
        const data = JSON.parse(atob(encoded));
        document.getElementById('cat_id').value = data.id;
        document.getElementById('cat_name').value = data.name;
        document.getElementById('cat_slug').value = data.slug;
        document.getElementById('slug_preview').innerText = '/' + data.slug;
        document.getElementById('modalTitle').innerText = 'Edit Category #' + data.id;
        openModal('saveModal');
    }
    function confirmDeleteWithTitle(id, title, page) {
        document.getElementById('del_item_name').innerText = '"' + title + '"';
        document.getElementById('delConfirm').href = page + '?delete_id=' + id;
        openModal('deleteModal');
    }
    function openModal(id) { document.getElementById(id).classList.remove('hidden'); }
    function closeModal(id) { document.getElementById(id).classList.add('hidden'); }
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('-translate-x-full');
        document.getElementById('overlay').classList.toggle('hidden');
    }
</script>
</body>
</html>