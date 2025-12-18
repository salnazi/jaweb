<?php
/**
 * Filename: admin/users.php
 * Last Update: 2025-12-18
 * Logic: Header Integration + Button Realignment (Menus Style)
 */

require_once '../db_config.php';
require_once 'auth_check.php';
require_once 'functions.php';

if (!has_role(ROLE_ADMIN)) {
    header("Location: dashboard.php?error=unauthorized");
    exit();
}

$current_module = basename($_SERVER['PHP_SELF']);
$table_users = TABLE_PREFIX . 'users';

// --- AJAX SEARCH ---
if(isset($_GET['search_query'])){
    $q = mysqli_real_escape_string($conn, $_GET['search_query']);
    $query = "SELECT id, username, role, created_at FROM $table_users WHERE username LIKE '%$q%' OR role LIKE '%$q%' ORDER BY id ASC";
    $result = mysqli_query($conn, $query);
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_assoc($result)){
            $encData = base64_encode(json_encode($row));
            $roleColor = ($row['role'] == 'admin') ? 'text-rose-500' : 'text-sky-500';
            ?>
            <tr class="hover:bg-slate-800/50 transition-colors text-sm">
                <td class="px-6 py-4 font-mono text-sky-500 font-bold">#<?=$row['id']?></td>
                <td class="px-6 py-4 font-semibold text-slate-200"><i class="fa-solid fa-circle-user mr-2 opacity-50"></i><?=htmlspecialchars($row['username'])?></td>
                <td class="px-6 py-4 font-bold uppercase text-[10px] <?=$roleColor?>"><?=$row['role']?></td>
                <td class="px-6 py-4 text-slate-500 text-xs"><?=date('d M Y', strtotime($row['created_at']))?></td>
                <td class="px-6 py-4 text-right">
                    <div class="flex justify-end gap-2">
                        <button onclick="previewItem('<?=$encData?>')" class="w-8 h-8 rounded bg-green-600 text-white"><i class="fa-solid fa-eye text-xs"></i></button>
                        <button onclick="editItem('<?=$encData?>')" class="w-8 h-8 rounded bg-blue-600 text-white"><i class="fa-solid fa-pen-to-square text-xs"></i></button>
                        <?php if($row['id'] != $_SESSION['admin_user_id']): ?>
                            <button onclick="confirmDeleteWithTitle(<?=$row['id']?>,'<?=htmlspecialchars($row['username'],ENT_QUOTES)?>','users.php')" class="w-8 h-8 rounded bg-red-600 text-white"><i class="fa-solid fa-trash-can text-xs"></i></button>
                        <?php else: ?>
                            <button class="w-8 h-8 rounded bg-slate-800 text-slate-600 cursor-not-allowed" title="Self"><i class="fa-solid fa-lock text-xs"></i></button>
                        <?php endif; ?>
                    </div>
                </td>
            </tr>
            <?php
        }
    } else { echo '<tr><td colspan="5" class="p-10 text-center text-slate-500 italic text-xs uppercase">No staff members found</td></tr>'; }
    exit();
}

// --- DELETE ---
if(isset($_GET['delete_id'])){
    $id = mysqli_real_escape_string($conn, $_GET['delete_id']);
    if($id != $_SESSION['admin_user_id']){
        mysqli_query($conn, "DELETE FROM $table_users WHERE id = '$id'");
        log_activity("Deleted user ID: $id");
    }
    header("Location: users.php"); exit();
}

// --- SAVE / UPDATE ---
if(isset($_POST['save_user'])){
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);
    $password = $_POST['password'];

    if(!empty($id)){
        $sql = "UPDATE $table_users SET username='$username', role='$role' WHERE id='$id'";
        mysqli_query($conn, $sql);
        if(!empty($password)){
            $hashed = password_hash($password, PASSWORD_BCRYPT);
            mysqli_query($conn, "UPDATE $table_users SET password='$hashed' WHERE id='$id'");
        }
    } else {
        $hashed = password_hash($password, PASSWORD_BCRYPT);
        mysqli_query($conn, "INSERT INTO $table_users (username, password, role, created_at) VALUES ('$username', '$hashed', '$role', NOW())");
    }
    header("Location: users.php"); exit();
}

$users = mysqli_query($conn, "SELECT id, username, role, created_at FROM $table_users ORDER BY id ASC");

include 'header.php';
?>

<div class="flex-1 overflow-y-auto p-6 lg:p-8">
    <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-black text-white uppercase tracking-tighter">Team Management</h2>
            <p class="text-slate-500 text-[10px] font-bold uppercase tracking-widest mt-1">Total Staff: <?= mysqli_num_rows($users) ?></p>
        </div>
        <div class="flex items-center gap-2">
            <div class="flex items-center bg-slate-900 border border-slate-800 rounded-lg px-3 py-1 gap-3 focus-within:border-sky-500 transition-all">
                <span class="text-[10px] font-bold text-slate-500 uppercase">Search:</span>
                <input type="text" id="ajaxSearch" placeholder="Search accounts..." class="bg-transparent border-none text-xs text-white outline-none w-full md:w-64 py-1.5">
            </div>
            <button onclick="window.history.back()" class="bg-slate-900 border border-slate-800 text-slate-400 hover:text-white px-3 py-2 rounded-lg text-[10px] font-bold uppercase flex items-center gap-1.5 transition-all">
                <i class="fa-solid fa-arrow-left"></i> Back
            </button>
            <button onclick="prepareNew()" class="bg-sky-600 hover:bg-sky-500 text-white px-4 py-2 rounded-lg text-[10px] font-bold uppercase flex items-center gap-2 shadow-lg transition-all">
                <i class="fa-solid fa-user-plus"></i> Add Account
            </button>
        </div>
    </div>

    <div class="bg-slate-900 border border-slate-800 rounded-xl overflow-hidden shadow-2xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left table-auto min-w-[700px]">
                <thead>
                    <tr class="bg-slate-950 border-b border-slate-800 text-[10px] font-bold text-slate-500 uppercase tracking-widest">
                        <th class="px-6 py-4 w-20">UID</th>
                        <th class="px-6 py-4">Username</th>
                        <th class="px-6 py-4">Role</th>
                        <th class="px-6 py-4">Joined On</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody id="userTableBody" class="divide-y divide-slate-800">
                    <?php while($row = mysqli_fetch_assoc($users)): 
                        $encData = base64_encode(json_encode($row)); 
                        $roleColor = ($row['role'] == 'admin') ? 'text-rose-500' : 'text-sky-500'; 
                    ?>
                        <tr class="hover:bg-slate-800/50 transition-colors text-sm">
                            <td class="px-6 py-4 font-mono text-sky-500 font-bold">#<?= $row['id'] ?></td>
                            <td class="px-6 py-4 font-semibold text-slate-200"><i class="fa-solid fa-circle-user mr-2 opacity-50"></i><?= htmlspecialchars($row['username']) ?></td>
                            <td class="px-6 py-4 font-bold uppercase text-[10px] <?= $roleColor ?>"><?= $row['role'] ?></td>
                            <td class="px-6 py-4 text-slate-500 text-xs"><?= date('d M Y', strtotime($row['created_at'])) ?></td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <button onclick="previewItem('<?= $encData ?>')" class="w-8 h-8 rounded bg-green-600 text-white hover:bg-green-500 transition-all shadow-md"><i class="fa-solid fa-eye text-xs"></i></button>
                                    <button onclick="editItem('<?= $encData ?>')" class="w-8 h-8 rounded bg-blue-600 text-white hover:bg-blue-500 transition-all shadow-md"><i class="fa-solid fa-pen-to-square text-xs"></i></button>
                                    <?php if($row['id'] != $_SESSION['admin_user_id']): ?>
                                        <button onclick="confirmDeleteWithTitle(<?= $row['id'] ?>,'<?= htmlspecialchars($row['username'],ENT_QUOTES) ?>','users.php')" class="w-8 h-8 rounded bg-red-600 text-white hover:bg-red-500 transition-all shadow-md"><i class="fa-solid fa-trash-can text-xs"></i></button>
                                    <?php else: ?>
                                        <button class="w-8 h-8 rounded bg-slate-800 text-slate-600 cursor-not-allowed" title="Cannot delete yourself"><i class="fa-solid fa-lock text-xs"></i></button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="saveModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4 bg-black/90 backdrop-blur-sm">
    <div class="bg-slate-900 w-full max-w-md rounded-2xl border border-slate-800 shadow-2xl overflow-hidden">
        <div class="p-6 border-b border-slate-800 bg-slate-950 flex justify-between items-center"><h3 class="text-lg font-black text-white uppercase tracking-tighter" id="modalTitle">Account Access</h3><button onclick="closeModal('saveModal')" class="text-slate-500 hover:text-white"><i class="fa-solid fa-xmark text-xl"></i></button></div>
        <form method="POST" class="p-8 space-y-4">
            <input type="hidden" name="id" id="user_id">
            <div><label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Username</label><input type="text" name="username" id="user_name" required class="w-full bg-slate-950 border border-slate-800 rounded-lg px-4 py-2.5 text-white text-sm focus:border-sky-500 outline-none"></div>
            <div><label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Password <span id="pass_hint" class="text-[8px] normal-case text-slate-600 ml-2">(Leave blank to keep current)</span></label><input type="password" name="password" id="user_pass" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-4 py-2.5 text-white text-sm focus:border-sky-500 outline-none"></div>
            <div><label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Access Level</label><select name="role" id="user_role" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-4 py-2.5 text-white text-sm focus:border-sky-500 outline-none"><option value="editor">Editor (Standard)</option><option value="admin">Administrator (Full Access)</option></select></div>
            <div class="flex gap-3 pt-4"><button type="submit" name="save_user" class="flex-1 bg-green-600 text-white py-3 rounded-lg font-bold text-sm uppercase shadow-lg hover:bg-green-500 transition-all">Confirm Ok</button><button type="button" onclick="closeModal('saveModal')" class="flex-1 bg-red-600 text-white py-3 rounded-lg font-bold text-sm uppercase hover:bg-red-500 transition-all">Cancel</button></div>
        </form>
    </div>
</div>

<div id="previewModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4 bg-black/90 backdrop-blur-sm">
    <div class="bg-slate-900 w-full max-w-md rounded-2xl border border-slate-800 shadow-2xl overflow-hidden">
        <div class="p-8 space-y-6 text-center">
            <div class="w-20 h-20 bg-sky-600/10 text-sky-500 rounded-full flex items-center justify-center mx-auto border border-sky-600/20 shadow-xl"><i class="fa-solid fa-user-shield text-3xl"></i></div>
            <div><h3 id="prev_name" class="text-2xl font-black text-white uppercase tracking-tighter"></h3><p id="prev_role" class="text-[10px] font-black uppercase tracking-widest mt-1"></p></div>
            <div class="grid grid-cols-2 gap-px bg-slate-800 rounded-xl overflow-hidden border border-slate-800">
                <div class="bg-slate-950 p-4"><label class="text-[8px] text-slate-500 uppercase font-black block mb-1">Internal UID</label><p id="prev_id" class="text-sky-500 font-bold font-mono text-sm"></p></div>
                <div class="bg-slate-950 p-4"><label class="text-[8px] text-slate-500 uppercase font-black block mb-1">Staff Since</label><p id="prev_date" class="text-slate-300 font-bold text-sm"></p></div>
            </div>
            <button onclick="closeModal('previewModal')" class="w-full bg-green-600 text-white py-3 rounded-lg font-bold text-sm uppercase shadow-lg shadow-green-900/20 hover:bg-green-500 transition-all">Ok, Got it</button>
        </div>
    </div>
</div>

<div id="deleteModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4 bg-black/90 backdrop-blur-sm">
    <div class="bg-slate-900 w-full max-w-md rounded-2xl p-8 border border-slate-800 text-center shadow-2xl">
        <div class="w-16 h-16 bg-red-600/10 text-red-600 rounded-full flex items-center justify-center mx-auto mb-6 border border-red-600/20"><i class="fa-solid fa-user-slash text-2xl"></i></div>
        <h3 class="text-xl font-black text-white mb-2 uppercase tracking-tighter">Revoke Access?</h3>
        <p class="text-slate-400 text-sm mb-8">This will permanently remove <span id="del_item_name" class="text-white font-bold underline"></span> from the system. Continue?</p>
        <div class="flex gap-3"><a id="delConfirm" href="#" class="flex-1 bg-green-600 text-white py-3 rounded-lg font-bold text-sm uppercase text-center leading-[48px] hover:bg-green-500 transition-all">Confirm Ok</a><button onclick="closeModal('deleteModal')" class="flex-1 bg-red-600 text-white py-3 rounded-lg font-bold text-sm uppercase hover:bg-red-500 transition-all">Cancel</button></div>
    </div>
</div>

</main> <script src="js/main.js"></script>
<script>
    function prepareNew(){
        document.getElementById('user_id').value=''; document.getElementById('user_name').value='';
        document.getElementById('user_pass').required = true; document.getElementById('pass_hint').classList.add('hidden');
        document.getElementById('modalTitle').innerText='Create New Account'; openModal('saveModal');
    }
    function editItem(encoded){
        const d=JSON.parse(atob(encoded));
        document.getElementById('user_id').value=d.id; document.getElementById('user_name').value=d.username;
        document.getElementById('user_role').value=d.role; document.getElementById('user_pass').required = false;
        document.getElementById('pass_hint').classList.remove('hidden');
        document.getElementById('modalTitle').innerText='Update Account'; openModal('saveModal');
    }
    function previewItem(encoded){
        const d=JSON.parse(atob(encoded));
        document.getElementById('prev_id').innerText = '#' + d.id;
        document.getElementById('prev_name').innerText = d.username;
        document.getElementById('prev_role').innerText = d.role;
        document.getElementById('prev_role').className = (d.role === 'admin') ? 'text-rose-500 text-[10px] font-black uppercase' : 'text-sky-500 text-[10px] font-black uppercase';
        document.getElementById('prev_date').innerText = d.created_at;
        openModal('previewModal');
    }
    function confirmDeleteWithTitle(id, title, page) {
        document.getElementById('del_item_name').innerText = title;
        document.getElementById('delConfirm').href = page + '?delete_id=' + id;
        openModal('deleteModal');
    }
    function openModal(id){document.getElementById(id).classList.remove('hidden');}
    function closeModal(id){document.getElementById(id).classList.add('hidden');}
    document.getElementById('ajaxSearch').addEventListener('input',function(){
        fetch('users.php?search_query='+encodeURIComponent(this.value)).then(r=>r.text()).then(html=>{
            document.getElementById('userTableBody').innerHTML=html;
        });
    });
    function toggleSidebar(){document.getElementById('sidebar').classList.toggle('-translate-x-full');document.getElementById('overlay').classList.toggle('hidden');}
</script>
</body>
</html>