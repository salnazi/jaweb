<?php
/**
 * Filename: admin/menus.php
 * Last Update: 2025-12-18
 * Rules No: 1 to 11
 * Details: Menu Management with menu_position column mapping.
 */

include 'header.php';

if (!has_role(ROLE_ADMIN)) {
    header("Location: dashboard.php?error=unauthorized");
    exit();
}

$table_menus = TABLE_PREFIX . 'menus';
$limit = 10;
$page = isset($_GET['p']) ? (int)$_GET['p'] : 1;
$offset = ($page - 1) * $limit;

// --- AJAX SEARCH ---
if (isset($_GET['search_query'])) {
    $q = mysqli_real_escape_string($conn, $_GET['search_query']);
    $query = "SELECT * FROM `$table_menus` WHERE `title` LIKE '%$q%' OR `url` LIKE '%$q%' ORDER BY `menu_position` ASC";
    $result = mysqli_query($conn, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $encData = base64_encode(json_encode($row));
            $statusText = ($row['is_active'] == 1) ? 'ACTIVE' : 'INACTIVE';
            $statusColor = ($row['is_active'] == 1) ? 'text-emerald-500' : 'text-rose-500';
            echo '<tr class="hover:bg-slate-800/50 transition-colors text-sm">';
            echo '<td class="px-6 py-4 font-mono text-sky-500 font-bold">#' . $row['link_id'] . '</td>';
            echo '<td class="px-6 py-4 font-semibold text-slate-200">' . htmlspecialchars($row['title']) . '</td>';
            echo '<td class="px-6 py-4 font-mono text-xs text-slate-400">' . htmlspecialchars($row['url']) . '</td>';
            echo '<td class="px-6 py-4 font-mono text-center">' . (int)$row['menu_position'] . '</td>';
            echo '<td class="px-6 py-4 font-bold uppercase text-[10px] ' . $statusColor . '">' . $statusText . '</td>';
            echo '<td class="px-6 py-4 text-right"><div class="flex justify-end gap-2">';
            echo '<button onclick="previewItem(\'' . $encData . '\')" class="w-8 h-8 rounded bg-green-600 text-white"><i class="fa-solid fa-eye text-xs"></i></button>';
            echo '<button onclick="editItem(\'' . $encData . '\')" class="w-8 h-8 rounded bg-blue-600 text-white"><i class="fa-solid fa-pen-to-square text-xs"></i></button>';
            echo '<button onclick="confirmDeleteWithTitle(' . $row['link_id'] . ',\'' . htmlspecialchars($row['title'], ENT_QUOTES) . '\',\'menus.php\')" class="w-8 h-8 rounded bg-red-600 text-white"><i class="fa-solid fa-trash-can text-xs"></i></button>';
            echo '</div></td></tr>';
        }
    } else {
        echo '<tr><td colspan="6" class="p-10 text-center text-slate-500 italic text-xs uppercase tracking-widest">No matching menus found</td></tr>';
    }
    exit();
}

// --- DELETE ---
if (isset($_GET['delete_id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['delete_id']);
    mysqli_query($conn, "DELETE FROM `$table_menus` WHERE `link_id` = '$id'");
    log_activity("Deleted menu item ID: $id", "DELETE");
    header("Location: menus.php");
    exit();
}

// --- SAVE / UPDATE ---
if (isset($_POST['save_menu'])) {
    $link_id = mysqli_real_escape_string($conn, $_POST['link_id']);
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $url = mysqli_real_escape_string($conn, $_POST['url']);
    $link_type = mysqli_real_escape_string($conn, $_POST['link_type'] ?? 'custom');
    $target = mysqli_real_escape_string($conn, $_POST['target'] ?? '_self');
    $slug = mysqli_real_escape_string($conn, $_POST['slug'] ?? '');
    $is_active = (int)$_POST['is_active'];
    $menu_position = (int)$_POST['menu_position'];

    if (!empty($link_id)) {
        $sql = "UPDATE `$table_menus` SET `title`='$title', `url`='$url', `link_type`='$link_type', `target`='$target', `slug`='$slug', `menu_position`='$menu_position', `is_active`='$is_active' WHERE `link_id`='$link_id'";
        log_activity("Updated menu item: $title", "UPDATE");
    } else {
        $sql = "INSERT INTO `$table_menus` (`title`, `url`, `link_type`, `target`, `slug`, `menu_position`, `is_active`) VALUES ('$title', '$url', '$link_type', '$target', '$slug', '$menu_position', '$is_active')";
        log_activity("Added new menu item: $title", "ADD");
    }
    
    if (!mysqli_query($conn, $sql)) {
        die("Error updating record: " . mysqli_error($conn));
    }
    header("Location: menus.php");
    exit();
}

$total_res = mysqli_query($conn, "SELECT COUNT(`link_id`) AS total FROM `$table_menus` ");
$total_rows = ($total_res) ? mysqli_fetch_assoc($total_res)['total'] : 0;
$total_pages = ceil($total_rows / $limit);
$menus = mysqli_query($conn, "SELECT * FROM `$table_menus` ORDER BY `menu_position` ASC LIMIT $offset, $limit");
if (!$menus) { die("Query Failed: " . mysqli_error($conn)); }
?>

<div class="flex-1 overflow-y-auto p-6 lg:p-8">
    <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-black text-white uppercase tracking-tighter">Menu Navigation</h2>
            <p class="text-slate-500 text-[10px] font-bold uppercase tracking-widest mt-1">Sorting by menu_position</p>
        </div>
        <div class="flex items-center gap-2">
            <div class="flex items-center bg-slate-900 border border-slate-800 rounded-lg px-3 py-1 gap-3 focus-within:border-sky-500">
                <span class="text-[10px] font-bold text-slate-500 uppercase whitespace-nowrap">Filter:</span>
                <input type="text" id="ajaxSearch" placeholder="Search title..." class="bg-transparent border-none text-xs text-white outline-none w-full md:w-64 py-1.5">
            </div>
            <button onclick="window.history.back()" class="bg-slate-900 border border-slate-800 text-slate-400 hover:text-white px-3 py-2 rounded-lg text-[10px] font-bold uppercase flex items-center gap-1.5"><i class="fa-solid fa-arrow-left"></i> Back</button>
            <button onclick="prepareNew()" class="bg-sky-600 hover:bg-sky-500 text-white px-4 py-2 rounded-lg text-[10px] font-bold uppercase flex items-center gap-2 shadow-lg"><i class="fa-solid fa-plus"></i> Add Menu</button>
        </div>
    </div>

    <div class="bg-slate-900 border border-slate-800 rounded-xl overflow-hidden shadow-2xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left table-auto min-w-[800px]">
                <thead>
                    <tr class="bg-slate-950 border-b border-slate-800 text-[10px] font-bold text-slate-500 uppercase tracking-widest">
                        <th class="px-6 py-4 w-20">ID</th>
                        <th class="px-6 py-4">Title</th>
                        <th class="px-6 py-4">URL</th>
                        <th class="px-6 py-4 text-center">Pos</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody id="menuTableBody" class="divide-y divide-slate-800">
                    <?php while ($row = mysqli_fetch_assoc($menus)):
                        $encData = base64_encode(json_encode($row));
                        $statusText = ($row['is_active'] == 1) ? 'ACTIVE' : 'INACTIVE';
                        $statusColor = ($row['is_active'] == 1) ? 'text-emerald-500' : 'text-rose-500';
                    ?>
                        <tr class="hover:bg-slate-800/50 transition-colors text-sm">
                            <td class="px-6 py-4 font-mono text-sky-500 font-bold">#<?= $row['link_id'] ?></td>
                            <td class="px-6 py-4 font-semibold text-slate-200"><?= htmlspecialchars($row['title']) ?></td>
                            <td class="px-6 py-4 font-mono text-xs text-slate-400"><?= htmlspecialchars($row['url']) ?></td>
                            <td class="px-6 py-4 font-mono text-center"><?= (int)$row['menu_position'] ?></td>
                            <td class="px-6 py-4 font-bold uppercase text-[10px] <?= $statusColor ?>"><?= $statusText ?></td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <button onclick="previewItem('<?= $encData ?>')" class="w-8 h-8 rounded bg-green-600 text-white shadow-md hover:bg-green-500"><i class="fa-solid fa-eye text-xs"></i></button>
                                    <button onclick="editItem('<?= $encData ?>')" class="w-8 h-8 rounded bg-blue-600 text-white shadow-md hover:bg-blue-500"><i class="fa-solid fa-pen-to-square text-xs"></i></button>
                                    <button onclick="confirmDeleteWithTitle(<?= $row['link_id'] ?>,'<?= htmlspecialchars($row['title'], ENT_QUOTES) ?>','menus.php')" class="w-8 h-8 rounded bg-red-600 text-white shadow-md hover:bg-red-500"><i class="fa-solid fa-trash-can text-xs"></i></button>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <div class="p-4 bg-slate-950/50 border-t border-slate-800 flex justify-end">
            <div class="flex gap-1" id="paginationNav">
                <?php if ($page > 1): ?><a href="?p=<?= $page - 1 ?>" class="px-3 py-1 bg-slate-800 hover:bg-sky-600 text-white text-xs rounded transition-colors">Prev</a><?php endif; ?>
                <?php for ($i = 1; $i <= $total_pages; $i++): ?><a href="?p=<?= $i ?>" class="px-3 py-1 <?= ($page == $i) ? 'bg-sky-600 text-white' : 'bg-slate-800 text-slate-500' ?> text-xs rounded transition-colors"><?= $i ?></a><?php endfor; ?>
                <?php if ($page < $total_pages): ?><a href="?p=<?= $page + 1 ?>" class="px-3 py-1 bg-slate-800 hover:bg-sky-600 text-white text-xs rounded transition-colors">Next</a><?php endif; ?>
            </div>
        </div>
    </div>
</div>
</main>

<div id="saveModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4 bg-black/90 backdrop-blur-sm">
    <div class="bg-slate-900 w-full max-w-md rounded-2xl border border-slate-800 shadow-2xl overflow-hidden">
        <div class="p-6 border-b border-slate-800 bg-slate-950 flex justify-between items-center">
            <h3 class="text-lg font-black text-white uppercase tracking-tighter" id="modalTitle">Menu Item</h3>
            <button onclick="closeModal('saveModal')" class="text-slate-500 hover:text-white"><i class="fa-solid fa-xmark text-xl"></i></button>
        </div>
        <form method="POST" class="p-8 space-y-4">
            <input type="hidden" name="link_id" id="form_link_id">
            <input type="hidden" name="link_type" id="form_link_type" value="custom">
            <input type="hidden" name="slug" id="form_slug" value="">
            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2"><label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Title</label><input type="text" name="title" id="form_title" required class="w-full bg-slate-950 border border-slate-800 rounded-lg px-4 py-2.5 text-white text-sm focus:border-sky-500 outline-none"></div>
                <div class="col-span-2"><label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">URL</label><input type="text" name="url" id="form_url" required class="w-full bg-slate-950 border border-slate-800 rounded-lg px-4 py-2.5 text-white text-sm focus:border-sky-500 outline-none"></div>
                <div><label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Target</label><select name="target" id="form_target" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-4 py-2.5 text-white text-sm focus:border-sky-500 outline-none"><option value="_self">Same Tab</option><option value="_blank">New Tab</option></select></div>
                <div><label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Position</label><input type="number" name="menu_position" id="form_menu_position" required class="w-full bg-slate-950 border border-slate-800 rounded-lg px-4 py-2.5 text-sky-500 text-sm font-mono focus:border-sky-500 outline-none"></div>
                <div class="col-span-2"><label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Status</label><select name="is_active" id="form_is_active" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-4 py-2.5 text-white text-sm focus:border-sky-500 outline-none"><option value="1">Active</option><option value="0">Inactive</option></select></div>
            </div>
            <div class="flex gap-3 pt-4"><button type="submit" name="save_menu" class="flex-1 bg-green-600 text-white py-3 rounded-lg font-bold text-sm uppercase shadow-lg">Confirm Ok</button><button type="button" onclick="closeModal('saveModal')" class="flex-1 bg-red-600 text-white py-3 rounded-lg font-bold text-sm uppercase">Cancel</button></div>
        </form>
    </div>
</div>

<div id="previewModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4 bg-black/90 backdrop-blur-sm">
    <div class="bg-slate-900 w-full max-w-md rounded-2xl border border-slate-800 shadow-2xl overflow-hidden">
        <div class="p-8 space-y-6">
            <div class="flex justify-between items-start">
                <div><h3 id="prev_title" class="text-2xl font-black text-white uppercase tracking-tighter leading-none"></h3><p class="text-slate-500 text-[10px] font-bold uppercase tracking-widest mt-2">Menu Item Preview</p></div>
                <div class="text-right"><label class="text-[9px] text-slate-500 uppercase font-black block">Record ID</label><p id="prev_id" class="text-sky-500 font-bold font-mono"></p></div>
            </div>
            <div class="space-y-4">
                <div><label class="text-[10px] text-slate-500 uppercase font-black mb-1 block">Full URL</label><div class="bg-slate-950 p-3 rounded border border-slate-800 text-xs font-mono text-slate-400 break-all" id="prev_url"></div></div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-slate-950 p-4 rounded-xl border border-slate-800 text-center"><label class="text-[9px] text-slate-500 uppercase font-black block mb-1">Position</label><p id="prev_position" class="text-sky-500 font-black font-mono text-xl"></p></div>
                    <div class="bg-slate-950 p-4 rounded-xl border border-slate-800 text-center"><label class="text-[9px] text-slate-500 uppercase font-black block mb-1">Status</label><p id="prev_status" class="font-black uppercase text-sm"></p></div>
                </div>
            </div>
            <button onclick="closeModal('previewModal')" class="w-full bg-green-600 text-white py-3 rounded-lg font-bold text-sm uppercase shadow-lg shadow-green-900/20">Ok, Got it</button>
        </div>
    </div>
</div>

<div id="deleteModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4 bg-black/90 backdrop-blur-sm">
    <div class="bg-slate-900 w-full max-w-md rounded-2xl p-8 border border-slate-800 text-center shadow-2xl">
        <div class="w-16 h-16 bg-red-600/10 text-red-600 rounded-full flex items-center justify-center mx-auto mb-6 border border-red-600/20"><i class="fa-solid fa-trash-can text-2xl"></i></div>
        <h3 class="text-xl font-black text-white mb-2 uppercase tracking-tighter">Remove Menu?</h3>
        <p class="text-slate-400 text-sm mb-8">This will delete <span id="del_item_name" class="text-white font-bold underline"></span>. Are you sure?</p>
        <div class="flex gap-3"><a id="delConfirm" href="#" class="flex-1 bg-green-600 text-white py-3 rounded-lg font-bold text-sm uppercase text-center leading-[48px] block">Confirm Ok</a><button onclick="closeModal('deleteModal')" class="flex-1 bg-red-600 text-white py-3 rounded-lg font-bold text-sm uppercase">Cancel</button></div>
    </div>
</div>

<script src="js/main.js"></script>
<script>
    function prepareNew() {
        document.getElementById('form_link_id').value = '';
        document.getElementById('form_title').value = '';
        document.getElementById('form_url').value = '';
        document.getElementById('form_menu_position').value = '0';
        document.getElementById('form_is_active').value = '1';
        document.getElementById('form_target').value = '_self';
        document.getElementById('modalTitle').innerText = 'Add New Menu';
        openModal('saveModal');
    }

    function editItem(encoded) {
        const d = JSON.parse(atob(encoded));
        document.getElementById('form_link_id').value = d.link_id;
        document.getElementById('form_title').value = d.title;
        document.getElementById('form_url').value = d.url;
        document.getElementById('form_menu_position').value = parseInt(d.menu_position);
        document.getElementById('form_is_active').value = d.is_active;
        document.getElementById('form_target').value = d.target || '_self';
        document.getElementById('modalTitle').innerText = 'Edit Menu Link';
        openModal('saveModal');
    }

    function previewItem(encoded) {
        const d = JSON.parse(atob(encoded));
        document.getElementById('prev_id').innerText = '#' + d.link_id;
        document.getElementById('prev_title').innerText = d.title;
        document.getElementById('prev_url').innerText = d.url;
        document.getElementById('prev_position').innerText = parseInt(d.menu_position);
        const pStat = document.getElementById('prev_status');
        pStat.innerText = (d.is_active == 1) ? 'ACTIVE' : 'INACTIVE';
        pStat.className = (d.is_active == 1) ? 'text-emerald-500 font-black uppercase text-sm' : 'text-rose-500 font-black uppercase text-sm';
        openModal('previewModal');
    }

    function confirmDeleteWithTitle(id, title, page) {
        document.getElementById('del_item_name').innerText = title;
        document.getElementById('delConfirm').href = page + '?delete_id=' + id;
        openModal('deleteModal');
    }

    function openModal(id) { document.getElementById(id).classList.remove('hidden'); }
    function closeModal(id) { document.getElementById(id).classList.add('hidden'); }

    document.getElementById('ajaxSearch').addEventListener('input', function() {
        fetch('menus.php?search_query=' + encodeURIComponent(this.value)).then(r => r.text()).then(html => {
            document.getElementById('menuTableBody').innerHTML = html;
            document.getElementById('paginationNav').style.display = this.value ? 'none' : 'flex';
        });
    });
</script>
</body>
</html>