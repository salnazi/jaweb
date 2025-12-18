<?php
/**
 * Filename: admin/leads.php
 * Last Update: 2025-12-17
 * Rules No: 1 to 18
 * Details: Leads module with aligned header (Search/Back) and bottom-right pagination.
 * Change Log: 
 * - Rule Update: Header layout unified (Search & Back button in the same row).
 * - Pagination: Moved to the bottom right of the table container.
 * - Logic: Using TABLE_PREFIX constant for all table operations.
 */

require_once '../db_config.php';
require_once 'auth_check.php';
require_once 'functions.php';

$current_module = basename($_SERVER['PHP_SELF']);
$leads_table = TABLE_PREFIX . 'leads';

$limit = 10;
$page = isset($_GET['p']) ? (int)$_GET['p'] : 1;
$offset = ($page - 1) * $limit;

// --- AJAX SEARCH ---
if(isset($_GET['search_query'])){
    $q = mysqli_real_escape_string($conn, $_GET['search_query']);
    $query = "SELECT * FROM $leads_table WHERE name LIKE '%$q%' OR email LIKE '%$q%' OR subject LIKE '%$q%' ORDER BY id DESC";
    $result = mysqli_query($conn, $query);
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_assoc($result)){
            $encData = base64_encode(json_encode($row));
            echo '<tr class="hover:bg-slate-800/50 transition-colors text-sm">';
            echo '<td class="px-6 py-4 mono text-sky-500 font-bold">#'.$row['id'].'</td>';
            echo '<td class="px-6 py-4 text-white font-semibold">'.htmlspecialchars($row['name']).'</td>';
            echo '<td class="px-6 py-4 text-slate-400 font-mono">'.htmlspecialchars($row['email']).'</td>';
            echo '<td class="px-6 py-4 truncate max-w-[200px]">'.htmlspecialchars($row['subject']).'</td>';
            echo '<td class="px-6 py-4 mono text-[11px] text-slate-500">'.$row['created_at'].'</td>';
            echo '<td class="px-6 py-4 text-right"><div class="flex justify-end gap-1">';
            echo '<button onclick="previewItem(\''.$encData.'\')" class="w-8 h-8 rounded bg-green-600 text-white shadow-md"><i class="fa-solid fa-eye text-xs"></i></button>';
            echo '<button onclick="confirmDeleteWithTitle('.$row['id'].',\''.htmlspecialchars($row['name'],ENT_QUOTES).'\',\'leads.php\')" class="w-8 h-8 rounded bg-red-600 text-white shadow-md"><i class="fa-solid fa-trash-can text-xs"></i></button>';
            echo '</div></td></tr>';
        }
    } else { echo '<tr><td colspan="6" class="p-10 text-center text-slate-500 italic text-xs uppercase tracking-widest">No matching leads found</td></tr>'; }
    exit();
}

// --- DELETE ---
if(isset($_GET['delete_id'])){
    $id = mysqli_real_escape_string($conn, $_GET['delete_id']);
    mysqli_query($conn, "DELETE FROM $leads_table WHERE id = '$id'");
    header("Location: leads.php"); exit();
}

$total_res = mysqli_query($conn, "SELECT COUNT(id) AS total FROM $leads_table");
$total_rows = mysqli_fetch_assoc($total_res)['total'];
$total_pages = ceil($total_rows / $limit);
$leads = mysqli_query($conn, "SELECT * FROM $leads_table ORDER BY id DESC LIMIT $offset, $limit");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Leads Management | Power Console</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="flex min-h-screen overflow-hidden bg-slate-950 text-slate-300">

    <div id="overlay" onclick="toggleSidebar()" class="fixed inset-0 bg-black/80 z-40 hidden lg:hidden"></div>

    <aside id="sidebar" class="fixed lg:static inset-y-0 left-0 w-64 bg-slate-900 border-r border-slate-800 z-50 flex flex-col transition-transform duration-300 -translate-x-full lg:translate-x-0">
        <div class="p-6 border-b border-slate-800"><h1 class="text-xl font-black text-white uppercase tracking-tighter">POWER<span class="text-sky-500">USER</span></h1></div>
        <nav class="flex-1 p-0 py-4 space-y-1 overflow-y-auto">
            <a href="dashboard.php" class="flex items-center gap-3 px-6 py-3 text-slate-400 hover:bg-slate-800 transition-colors"><i class="fa-solid fa-gauge-high w-4"></i> Dashboards</a>
            <a href="leads.php" class="flex items-center gap-3 px-6 py-3 bg-sky-600 text-white font-bold border-l-4 border-sky-400 shadow-lg shadow-sky-900/20"><i class="fa-solid fa-envelope-open-text w-4"></i> Leads</a>
            <a href="categories.php" class="flex items-center gap-3 px-6 py-3 text-slate-400 hover:bg-slate-800 transition-colors"><i class="fa-solid fa-tags w-4"></i> Categories</a>
            <a href="portfolio.php" class="flex items-center gap-3 px-6 py-3 text-slate-400 hover:bg-slate-800 transition-colors"><i class="fa-solid fa-briefcase w-4"></i> Portfolio</a>
            <a href="menus.php" class="flex items-center gap-3 px-6 py-3 text-slate-400 hover:bg-slate-800 transition-colors"><i class="fa-solid fa-bars w-4"></i> Menus</a>
            <a href="settings.php" class="flex items-center gap-3 px-6 py-3 text-slate-400 hover:bg-slate-800 transition-colors"><i class="fa-solid fa-gear w-4"></i> Settings</a>
            <a href="users.php" class="flex items-center gap-3 px-6 py-3 text-slate-400 hover:bg-slate-800 transition-colors"><i class="fa-solid fa-user-shield w-4"></i> Users</a>
        </nav>
    </aside>

    <main class="flex-1 flex flex-col overflow-hidden">
        <header class="h-16 bg-slate-900 border-b border-slate-800 flex items-center justify-between px-6 shrink-0">
            <button onclick="toggleSidebar()" class="lg:hidden text-slate-400"><i class="fa-solid fa-bars-staggered text-xl"></i></button>
            <div class="flex items-center gap-2 ml-auto">
                <a href="logout.php" class="bg-red-600 hover:bg-red-500 text-white px-4 py-2 rounded-lg text-xs font-bold uppercase flex items-center gap-2 transition-all"><i class="fa-solid fa-power-off"></i> Logout</a>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-6 lg:p-8">
            <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-black text-white uppercase tracking-tighter">Inquiry Leads</h2>
                    <p class="text-slate-500 text-[10px] font-bold uppercase tracking-widest mt-1">Config: <?= TABLE_PREFIX ?>leads</p>
                </div>
                <div class="flex items-center gap-2">
                    <div class="flex items-center bg-slate-900 border border-slate-800 rounded-lg px-3 py-1 gap-3 focus-within:border-sky-500">
                        <span class="text-[10px] font-bold text-slate-500 uppercase whitespace-nowrap">Filter:</span>
                        <input type="text" id="ajaxSearch" placeholder="Search leads..." class="bg-transparent border-none text-xs text-white outline-none w-full md:w-64 py-1.5">
                        <i class="fa-solid fa-magnifying-glass text-slate-600 text-xs"></i>
                    </div>
                    <button onclick="window.history.back()" class="bg-slate-900 border border-slate-800 text-slate-400 hover:text-white px-3 py-2 rounded-lg text-[10px] font-bold uppercase flex items-center gap-1.5 transition-all"><i class="fa-solid fa-arrow-left"></i> Back</button>
                </div>
            </div>

            <div class="bg-slate-900 border border-slate-800 rounded-xl overflow-hidden shadow-2xl">
                <div class="overflow-x-auto">
                    <table class="w-full text-left table-auto min-w-[900px]">
                        <thead>
                            <tr class="bg-slate-950 border-b border-slate-800 text-[10px] font-bold text-slate-500 uppercase tracking-widest">
                                <th class="px-6 py-4 w-20">ID</th>
                                <th class="px-6 py-4">Sender</th>
                                <th class="px-6 py-4">Email</th>
                                <th class="px-6 py-4">Subject</th>
                                <th class="px-6 py-4">Date Received</th>
                                <th class="px-6 py-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="leadsTableBody" class="divide-y divide-slate-800">
                            <?php while($row = mysqli_fetch_assoc($leads)): $encData = base64_encode(json_encode($row)); ?>
                                <tr class="hover:bg-slate-800/50 transition-colors text-sm">
                                    <td class="px-6 py-4 mono text-sky-500 font-bold">#<?= $row['id'] ?></td>
                                    <td class="px-6 py-4 text-white font-semibold"><?= htmlspecialchars($row['name']) ?></td>
                                    <td class="px-6 py-4 text-slate-400 font-mono"><?= htmlspecialchars($row['email']) ?></td>
                                    <td class="px-6 py-4 truncate max-w-[200px]"><?= htmlspecialchars($row['subject']) ?></td>
                                    <td class="px-6 py-4 mono text-[11px] text-slate-500"><?= $row['created_at'] ?></td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex justify-end gap-1">
                                            <button onclick="previewItem('<?= $encData ?>')" class="w-8 h-8 rounded bg-green-600 text-white shadow-md"><i class="fa-solid fa-eye text-xs"></i></button>
                                            <button onclick="confirmDeleteWithTitle(<?= $row['id'] ?>,'<?= htmlspecialchars($row['name'],ENT_QUOTES) ?>','leads.php')" class="w-8 h-8 rounded bg-red-600 text-white shadow-md"><i class="fa-solid fa-trash-can text-xs"></i></button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                <div class="p-4 bg-slate-950/50 border-t border-slate-800 flex justify-end">
                    <div class="flex gap-1">
                        <?php if($page > 1): ?>
                            <a href="?p=<?= $page-1 ?>" class="px-3 py-1 bg-slate-800 hover:bg-sky-600 text-white text-xs rounded transition-colors">Prev</a>
                        <?php endif; ?>
                        <?php for($i=1; $i<=$total_pages; $i++): ?>
                            <a href="?p=<?= $i ?>" class="px-3 py-1 <?= ($i==$page)?'bg-sky-600':'bg-slate-800 hover:bg-slate-700' ?> text-white text-xs rounded transition-colors"><?= $i ?></a>
                        <?php endfor; ?>
                        <?php if($page < $total_pages): ?>
                            <a href="?p=<?= $page+1 ?>" class="px-3 py-1 bg-slate-800 hover:bg-sky-600 text-white text-xs rounded transition-colors">Next</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <div id="previewModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4 bg-black/90 backdrop-blur-sm">
        <div class="bg-slate-900 w-full max-w-2xl rounded-2xl border border-slate-800 shadow-2xl overflow-hidden max-h-[90vh] flex flex-col">
            <div class="p-6 border-b border-slate-800 bg-slate-950 flex justify-between items-center">
                <h3 class="text-lg font-black text-white uppercase tracking-tighter">Inquiry Information</h3>
                <button onclick="closeModal('previewModal')" class="text-slate-500 hover:text-white"><i class="fa-solid fa-xmark text-xl"></i></button>
            </div>
            <div class="p-8 space-y-6 overflow-y-auto">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div><label class="text-[9px] text-slate-500 uppercase font-black block mb-1">Full Name</label><p id="prev_name" class="text-white font-bold text-lg"></p></div>
                    <div><label class="text-[9px] text-slate-500 uppercase font-black block mb-1">Email Address</label><p id="prev_email" class="text-sky-500 font-mono"></p></div>
                    <div><label class="text-[9px] text-slate-500 uppercase font-black block mb-1">Phone Number</label><p id="prev_phone" class="text-slate-300"></p></div>
                    <div><label class="text-[9px] text-slate-500 uppercase font-black block mb-1">Lead ID & Date</label><p id="prev_meta" class="text-slate-400 mono text-xs"></p></div>
                </div>
                <div class="border-t border-slate-800 pt-4">
                    <label class="text-[9px] text-slate-500 uppercase font-black block mb-2">Subject</label>
                    <p id="prev_subject" class="text-white font-bold bg-slate-950 p-4 rounded border border-slate-800"></p>
                </div>
                <div>
                    <label class="text-[10px] text-slate-500 uppercase font-black mb-2 block">Message Content</label>
                    <div id="prev_message" class="bg-slate-950 p-6 rounded-lg border border-slate-800 text-slate-400 text-xs leading-relaxed whitespace-pre-wrap min-h-[100px]"></div>
                </div>
                <div class="flex justify-between items-center text-[10px] text-slate-600 font-bold uppercase border-t border-slate-800 pt-4 italic">
                    <span>Origin IP: <span id="prev_ip" class="text-slate-500 not-italic"></span></span>
                    <span class="text-emerald-500">Verified Submission</span>
                </div>
                <button onclick="closeModal('previewModal')" class="w-full bg-green-600 hover:bg-green-500 text-white py-3 rounded-lg font-bold text-sm uppercase transition-all shadow-lg shadow-green-900/20">Close Preview</button>
            </div>
        </div>
    </div>

    <script src="js/main.js"></script>
    <script>
        function previewItem(encoded){
            const d = JSON.parse(atob(encoded));
            document.getElementById('prev_name').innerText = d.name;
            document.getElementById('prev_email').innerText = d.email;
            document.getElementById('prev_phone').innerText = d.phone || 'Not provided';
            document.getElementById('prev_meta').innerText = '#' + d.id + ' | Received: ' + d.created_at;
            document.getElementById('prev_subject').innerText = d.subject;
            document.getElementById('prev_message').innerText = d.message;
            document.getElementById('prev_ip').innerText = d.ip_address || 'Unavailable';
            openModal('previewModal');
        }
        function openModal(id){document.getElementById(id).classList.remove('hidden');}
        function closeModal(id){document.getElementById(id).classList.add('hidden');}
        document.getElementById('ajaxSearch').addEventListener('input', function(){
            fetch('leads.php?search_query=' + encodeURIComponent(this.value))
                .then(r => r.text()).then(html => document.getElementById('leadsTableBody').innerHTML = html);
        });
        function toggleSidebar(){document.getElementById('sidebar').classList.toggle('-translate-x-full');document.getElementById('overlay').classList.toggle('hidden');}
    </script>
</body>
</html>