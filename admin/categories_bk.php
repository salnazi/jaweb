<?php
/**
 * Filename: admin/categories.php
 * Theme: Modern Enterprise (Tailwind CSS)
 * Timezone: Asia/Kolkata (GMT +5:30)
 */

require_once '../db_config.php'; 
require_once 'auth_check.php';
require_once 'functions.php';

date_default_timezone_set('Asia/Kolkata');
$prefix = TABLE_PREFIX;

// --- PAGINATION LOGIC ---
$limit = 10; 
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$total_results = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM {$prefix}categories"))['total'];
$total_pages = ceil($total_results / $limit);

$categories = mysqli_query($conn, "SELECT * FROM {$prefix}categories ORDER BY id DESC LIMIT $offset, $limit");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category Management | JAWeb</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; }
        .sidebar-gradient { background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%); }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    </style>
</head>
<body class="text-slate-800">

<div class="flex min-h-screen">
    <aside class="w-64 sidebar-gradient text-slate-300 hidden md:flex flex-col sticky top-0 h-screen">
        <div class="p-6">
            <h1 class="text-white text-2xl font-bold tracking-tight">JA<span class="text-indigo-400">WEB</span></h1>
            <p class="text-xs text-slate-500 mt-1 uppercase tracking-widest font-semibold">Admin Console</p>
        </div>
        
        <nav class="flex-1 px-4 space-y-1 custom-scrollbar overflow-y-auto">
            <a href="dashboard.php" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg hover:bg-slate-800 hover:text-white transition">
                <i class="fa-solid fa-house w-6"></i> Dashboard
            </a>
            <a href="portfolio.php" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg hover:bg-slate-800 hover:text-white transition">
                <i class="fa-solid fa-layer-group w-6"></i> Portfolio
            </a>
            <a href="categories.php" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg bg-indigo-600 text-white shadow-lg shadow-indigo-500/20">
                <i class="fa-solid fa-tags w-6"></i> Categories
            </a>
            <a href="system_logs.php" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg hover:bg-slate-800 hover:text-white transition">
                <i class="fa-solid fa-clock-rotate-left w-6"></i> System Logs
            </a>
        </nav>

        <div class="p-4 border-t border-slate-800">
            <a href="logout.php" class="flex items-center px-4 py-3 text-sm font-medium text-red-400 hover:bg-red-500/10 rounded-lg transition">
                <i class="fa-solid fa-right-from-bracket w-6"></i> Logout
            </a>
        </div>
    </aside>

    <main class="flex-1 flex flex-col min-w-0 overflow-hidden">
        <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-8 sticky top-0 z-10">
            <div class="flex items-center space-x-4">
                <div class="bg-indigo-50 text-indigo-600 px-3 py-1 rounded-full text-xs font-bold border border-indigo-100 uppercase">
                    <i class="fa-solid fa-location-dot mr-1"></i> Asia/Kolkata
                </div>
                <div id="clock" class="text-sm font-semibold text-slate-600 tracking-wider">
                    <?= date('h:i:s A') ?>
                </div>
            </div>
            
            <div class="flex items-center space-x-4">
                <span class="text-sm text-slate-500 italic">Welcome, Administrator</span>
                <div class="h-8 w-8 rounded-full bg-indigo-500 flex items-center justify-center text-white text-xs font-bold shadow-md">
                    AD
                </div>
            </div>
        </header>

        <div class="p-8 overflow-y-auto">
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 space-y-4 md:space-y-0">
                <div>
                    <h2 class="text-2xl font-bold text-slate-900">Category Management</h2>
                    <p class="text-slate-500 text-sm">Organize and filter your digital assets effectively.</p>
                </div>
                <button onclick="toggleModal('addModal')" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-lg font-semibold shadow-lg shadow-indigo-200 transition-all active:scale-95 flex items-center justify-center">
                    <i class="fa-solid fa-plus mr-2 text-xs"></i> Create New Category
                </button>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="p-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <div class="relative w-72">
                        <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <input type="text" id="liveSearch" placeholder="Search categories..." class="w-full pl-10 pr-4 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse" id="categoryTable">
                        <thead>
                            <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider font-bold">
                                <th class="px-6 py-4">ID</th>
                                <th class="px-6 py-4">Category Name</th>
                                <th class="px-6 py-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <?php while($row = mysqli_fetch_assoc($categories)): ?>
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="px-6 py-4 text-sm font-medium text-slate-400">#<?= $row['id'] ?></td>
                                <td class="px-6 py-4 text-sm font-semibold text-slate-700"><?= $row['name'] ?></td>
                                <td class="px-6 py-4 text-right space-x-2">
                                    <button class="p-2 text-emerald-600 hover:bg-emerald-50 rounded-md transition" title="View">
                                        <i class="fa-solid fa-eye text-sm"></i>
                                    </button>
                                    <button class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-md transition" title="Edit">
                                        <i class="fa-solid fa-pen-to-square text-sm"></i>
                                    </button>
                                    <button onclick="confirmDelete(<?= $row['id'] ?>)" class="p-2 text-rose-600 hover:bg-rose-50 rounded-md transition" title="Delete">
                                        <i class="fa-solid fa-trash-can text-sm"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

                <div class="p-4 bg-slate-50/50 border-t border-slate-100 flex items-center justify-between">
                    <p class="text-xs text-slate-500 font-medium">Showing page <?= $page ?> of <?= $total_pages ?></p>
                    <div class="flex space-x-1">
                        <?php for($i=1; $i<=$total_pages; $i++): ?>
                            <a href="?page=<?= $i ?>" class="px-3 py-1 text-xs font-bold rounded <?= ($page == $i) ? 'bg-indigo-600 text-white' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' ?>">
                                <?= $i ?>
                            </a>
                        <?php endfor; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<div id="addModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center">
            <h3 class="text-lg font-bold text-slate-900">New Category</h3>
            <button onclick="toggleModal('addModal')" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form method="POST" class="p-6">
            <label class="block text-sm font-semibold text-slate-700 mb-2">Category Name</label>
            <input type="text" name="name" required placeholder="e.g. Web Development" class="w-full px-4 py-2.5 border border-slate-200 rounded-lg focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition mb-6">
            <div class="flex space-x-3">
                <button type="button" onclick="toggleModal('addModal')" class="flex-1 px-4 py-2.5 bg-slate-100 text-slate-600 font-bold rounded-lg hover:bg-slate-200 transition">Cancel</button>
                <button type="submit" name="add_cat" class="flex-1 px-4 py-2.5 bg-indigo-600 text-white font-bold rounded-lg hover:bg-indigo-700 shadow-lg shadow-indigo-200 transition">Save Category</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Real-time Clock (Asia/Kolkata)
    function updateTime() {
        const options = { timeZone: 'Asia/Kolkata', hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true };
        const formatter = new Intl.DateTimeFormat('en-US', options);
        document.getElementById('clock').textContent = formatter.format(new Date());
    }
    setInterval(updateTime, 1000);

    // Live Search Logic
    document.getElementById('liveSearch').addEventListener('input', (e) => {
        const term = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('#categoryTable tbody tr');
        rows.forEach(row => {
            const name = row.cells[1].textContent.toLowerCase();
            row.style.display = name.includes(term) ? '' : 'none';
        });
    });

    function toggleModal(id) {
        document.getElementById(id).classList.toggle('hidden');
    }

    function confirmDelete(id) {
        if(confirm('Are you sure you want to remove this category?')) {
            window.location.href = `categories.php?delete_id=${id}`;
        }
    }
</script>

</body>
</html>