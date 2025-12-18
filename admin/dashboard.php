<?php
/**
 * Filename: admin/dashboard.php
 * Last Update: 2025-12-17
 * Rules No: 1 to 11
 * Details: Dashboard content. Uses global table variables from header.php.
 */

include 'header.php';

// Fetch Leads Count
$leads_q = mysqli_query($conn, "SELECT COUNT(id) as total FROM $table_leads");
$leads_count = ($leads_q) ? mysqli_fetch_assoc($leads_q)['total'] : 0;

// Fetch Logs Count
$logs_count_q = mysqli_query($conn, "SELECT COUNT(id) as total FROM $table_logs");
$log_count = ($logs_count_q) ? mysqli_fetch_assoc($logs_count_q)['total'] : 0;

// Fetch Recent Activity (id, user, action_type, module, action, created_at)
$logs = mysqli_query($conn, "SELECT id, user, action_type, module, action, created_at FROM $table_logs ORDER BY created_at DESC LIMIT 15");
//echo "SELECT id, user, action_type, module, action, created_at FROM $table_logs ORDER BY created_at DESC LIMIT 15";
?>

<div class="flex-1 overflow-y-auto p-6 lg:p-8">
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-black text-white uppercase tracking-tighter">System Command Center</h2>
            <p class="text-slate-500 text-[10px] font-bold uppercase tracking-widest mt-1">Operational Overview</p>
        </div>
        <button onclick="window.history.back()" class="bg-slate-900 border border-slate-800 text-slate-400 hover:text-white px-4 py-2 rounded-lg text-[10px] font-bold uppercase flex items-center gap-2">
            <i class="fa-solid fa-arrow-left"></i> Back
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <div class="bg-slate-900 border border-slate-800 p-6 rounded-2xl shadow-xl">
            <div class="flex items-center justify-between mb-4">
                <i class="fa-solid fa-envelope-open-text text-sky-500 text-2xl"></i>
                <span class="text-[10px] font-bold text-slate-500 uppercase">Communications</span>
            </div>
            <h3 class="text-3xl font-black text-white"><?= number_format($leads_count) ?></h3>
            <p class="text-slate-500 text-[10px] font-bold uppercase mt-1">Total Leads</p>
        </div>
        <div class="bg-slate-900 border border-slate-800 p-6 rounded-2xl shadow-xl border-l-4 border-l-sky-500">
            <div class="flex items-center justify-between mb-4">
                <i class="fa-solid fa-list-check text-sky-500 text-2xl"></i>
                <span class="text-[10px] font-bold text-slate-500 uppercase">Activity Log</span>
            </div>
            <h3 class="text-3xl font-black text-white"><?= number_format($log_count) ?></h3>
            <p class="text-slate-500 text-[10px] font-bold uppercase mt-1">Total Actions</p>
        </div>
        <div class="bg-slate-900 border border-slate-800 p-6 rounded-2xl shadow-xl">
            <div class="flex items-center justify-between mb-4">
                <i class="fa-solid fa-shield-halved text-green-500 text-2xl"></i>
                <span class="text-[10px] font-bold text-slate-500 uppercase">System Status</span>
            </div>
            <h3 class="text-3xl font-black text-white">Online</h3>
            <p class="text-slate-500 text-[10px] font-bold uppercase mt-1">Node Active</p>
        </div>
    </div>

    <div class="bg-slate-900 border border-slate-800 rounded-xl overflow-hidden shadow-2xl">
        <div class="p-6 border-b border-slate-800 bg-slate-950/50 flex justify-between items-center">
            <h3 class="text-sm font-black text-white uppercase tracking-widest">Recent Activity</h3>
            <span class="bg-slate-950 text-sky-500 text-[9px] px-2 py-1 rounded font-bold uppercase border border-slate-800">Live Feed</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-950 border-b border-slate-800">
                        <th class="px-6 py-4 text-[10px] font-black text-slate-500 uppercase">Timestamp</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-500 uppercase">Type</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-500 uppercase">Module</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-500 uppercase">Description</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-500 uppercase text-right">Operator</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/50">
                    <?php if ($logs && mysqli_num_rows($logs) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($logs)): ?>
                            <tr class="hover:bg-slate-800/30 transition-colors">
                                <td class="px-6 py-4 text-xs font-mono text-slate-400 whitespace-nowrap"><?= $row['created_at'] ?></td>
                                <td class="px-6 py-4">
                                    <?php 
                                        $type = strtoupper($row['action_type']);
                                        $clr = match($type) {
                                            'ADD', 'INSERT' => 'text-green-500 bg-green-500/10 border-green-500/20',
                                            'UPDATE', 'EDIT' => 'text-blue-500 bg-blue-500/10 border-blue-500/20',
                                            'DELETE' => 'text-red-500 bg-red-500/10 border-red-500/20',
                                            default => 'text-slate-500 bg-slate-500/10 border-slate-800'
                                        };
                                    ?>
                                    <span class="<?= $clr ?> px-2 py-1 rounded text-[9px] font-black uppercase border"><?= $type ?></span>
                                </td>
                                <td class="px-6 py-4 text-xs font-bold text-sky-500 italic"><?= htmlspecialchars($row['module']) ?></td>
                                <td class="px-6 py-4 text-sm text-slate-300"><?= htmlspecialchars($row['action']) ?></td>
                                <td class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase"><?= htmlspecialchars($row['user']) ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-500 text-[10px] font-black uppercase tracking-widest italic">No activity logs recorded</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</main>
<script src="js/main.js"></script>
<script>
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('-translate-x-full');
        document.getElementById('overlay').classList.toggle('hidden');
    }
    setInterval(() => {
        const now = new Date();
        const clock = document.getElementById('headerClock');
        if(clock) clock.textContent = now.toTimeString().split(' ')[0];
    }, 1000);
</script>
</body>
</html>