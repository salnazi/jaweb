<?php
/**
 * Filename: admin/settings.php
 * Last Update: 2025-12-17
 * Rules No: 1 to 11
 * Details: Site Configuration with dynamic branding. Uses header.php.
 * Change Log:
 * - Integrated header.php split.
 * - Corrected table name to activity_log.
 * - Standardized Modal styling for Confirm OK (Green) and Cancel (Red).
 */

include 'header.php';

if (!has_role(ROLE_ADMIN)) {
    header("Location: dashboard.php?error=unauthorized");
    exit();
}

$show_success = false;

// --- SETTINGS UPDATE ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_settings'])) {
    foreach ($_POST['settings'] as $key => $value) {
        $clean_key   = mysqli_real_escape_string($conn, $key);
        $clean_value = mysqli_real_escape_string($conn, $value);
        mysqli_query($conn, "UPDATE $table_settings SET setting_value = '$clean_value' WHERE setting_key = '$clean_key'");
    }
    log_activity("Updated global site settings");
    $show_success = true;
}

// --- MAINTENANCE ACTIONS ---
if (isset($_POST['action'])) {
    if ($_POST['action'] === 'clear_logs') {
        mysqli_query($conn, "TRUNCATE TABLE $table_logs");
        log_activity("System logs cleared by admin");
        $show_success = true;
    }
    if ($_POST['action'] === 'optimize') {
        mysqli_query($conn, "OPTIMIZE TABLE $table_settings, $table_logs");
        log_activity("Database optimization executed");
        $show_success = true;
    }
}

$result = mysqli_query($conn, "SELECT * FROM $table_settings ORDER BY setting_key ASC");
if (!$result) {
    die("Database Query Failed: " . mysqli_error($conn));
}
?>

        <div class="flex-1 overflow-y-auto p-6 lg:p-8">
            <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-black text-white uppercase tracking-tighter">Site Configuration</h2>
                    <p class="text-slate-500 text-[10px] font-bold uppercase tracking-widest mt-1">Global System Parameters</p>
                </div>
                <button onclick="window.history.back()" class="bg-slate-900 border border-slate-800 text-slate-400 hover:text-white px-4 py-2 rounded-lg text-[10px] font-bold uppercase flex items-center gap-2">
                    <i class="fa-solid fa-arrow-left"></i> Back
                </button>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2">
                    <form action="settings.php" method="POST" class="bg-slate-900 border border-slate-800 rounded-xl overflow-hidden shadow-2xl">
                        <div class="p-6 border-b border-slate-800 bg-slate-950/50 flex items-center gap-3">
                            <i class="fa-solid fa-sliders text-sky-500"></i>
                            <h3 class="font-bold text-sm uppercase tracking-widest text-white">General Settings</h3>
                        </div>
                        <div class="p-6 space-y-6">
                            <?php while ($s = mysqli_fetch_assoc($result)): ?>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center border-b border-slate-800/50 pb-4 last:border-0 last:pb-0">
                                    <label class="text-[10px] font-black text-slate-500 uppercase"><?= str_replace('_', ' ', $s['setting_key']) ?></label>
                                    <div class="md:col-span-2">
                                        <input type="text" name="settings[<?= $s['setting_key'] ?>]" value="<?= htmlspecialchars($s['setting_value']) ?>" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-4 py-2.5 text-sm text-white focus:border-sky-500 outline-none transition-all">
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </div>
                        <div class="p-6 bg-slate-950/50 border-t border-slate-800 text-right">
                            <button type="submit" name="update_settings" class="bg-sky-600 hover:bg-sky-500 text-white px-8 py-3 rounded-lg font-bold text-xs uppercase shadow-lg shadow-sky-900/20 flex items-center gap-2 ml-auto">
                                <i class="fa-solid fa-save"></i> Save All Changes
                            </button>
                        </div>
                    </form>
                </div>

                <div class="space-y-6">
                    <div class="bg-slate-900 border border-slate-800 rounded-xl overflow-hidden shadow-2xl">
                        <div class="p-6 border-b border-slate-800 bg-slate-950/50 flex items-center gap-3 text-emerald-500">
                            <i class="fa-solid fa-microchip"></i>
                            <h3 class="font-bold text-sm uppercase tracking-widest text-white">Maintenance</h3>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="p-4 bg-slate-950 rounded-lg border border-slate-800">
                                <h4 class="text-xs font-bold text-slate-200 mb-1">Database Optimization</h4>
                                <p class="text-[10px] text-slate-500 mb-3">Rebuilds indexes and frees up unused space.</p>
                                <button onclick="openModal('optModal')" class="w-full bg-emerald-600/10 text-emerald-500 border border-emerald-600/20 py-2 rounded font-bold text-[10px] uppercase hover:bg-emerald-600 hover:text-white transition-all">Execute Optimizer</button>
                            </div>
                            <div class="p-4 bg-slate-950 rounded-lg border border-slate-800">
                                <h4 class="text-xs font-bold text-slate-200 mb-1">System Logs</h4>
                                <p class="text-[10px] text-slate-500 mb-3">Permanently delete all activity history.</p>
                                <button onclick="openModal('logModal')" class="w-full bg-rose-600/10 text-rose-500 border border-rose-600/20 py-2 rounded font-bold text-[10px] uppercase hover:bg-rose-600 hover:text-white transition-all">Clear Activity Log</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <div id="optModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4 bg-black/90 backdrop-blur-sm">
        <div class="bg-slate-900 w-full max-w-sm rounded-2xl p-8 border border-slate-800 text-center shadow-2xl">
            <div class="w-16 h-16 bg-emerald-600/10 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-6 border border-emerald-600/20"><i class="fa-solid fa-wand-magic-sparkles text-2xl"></i></div>
            <h3 class="text-xl font-black text-white mb-2 uppercase tracking-tighter">Optimize DB?</h3>
            <p class="text-slate-400 text-sm mb-8">This will clean up overhead in the system tables.</p>
            <form method="POST" class="flex gap-3">
                <input type="hidden" name="action" value="optimize">
                <button type="submit" class="flex-1 bg-green-600 text-white py-3 rounded-lg font-bold text-sm uppercase">Confirm Ok</button>
                <button type="button" onclick="closeModal('optModal')" class="flex-1 bg-red-600 text-white py-3 rounded-lg font-bold text-sm uppercase">Cancel</button>
            </form>
        </div>
    </div>

    <div id="logModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4 bg-black/90 backdrop-blur-sm">
        <div class="bg-slate-900 w-full max-w-sm rounded-2xl p-8 border border-slate-800 text-center shadow-2xl">
            <div class="w-16 h-16 bg-rose-600/10 text-rose-600 rounded-full flex items-center justify-center mx-auto mb-6 border border-rose-600/20"><i class="fa-solid fa-trash-can text-2xl"></i></div>
            <h3 class="text-xl font-black text-white mb-2 uppercase tracking-tighter">Wipe Logs?</h3>
            <p class="text-slate-400 text-sm mb-8">All history will be lost. This cannot be undone.</p>
            <form method="POST" class="flex gap-3">
                <input type="hidden" name="action" value="clear_logs">
                <button type="submit" class="flex-1 bg-green-600 text-white py-3 rounded-lg font-bold text-sm uppercase">Confirm Ok</button>
                <button type="button" onclick="closeModal('logModal')" class="flex-1 bg-red-600 text-white py-3 rounded-lg font-bold text-sm uppercase">Cancel</button>
            </form>
        </div>
    </div>

    <?php if ($show_success): ?>
        <div id="successModal" class="fixed inset-0 z-[110] flex items-center justify-center p-4 bg-black/90 backdrop-blur-sm">
            <div class="bg-slate-900 w-full max-w-sm rounded-2xl p-8 border border-emerald-500/30 text-center shadow-2xl">
                <div class="w-16 h-16 bg-emerald-600 text-white rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg shadow-emerald-900/40"><i class="fa-solid fa-check text-2xl"></i></div>
                <h3 class="text-xl font-black text-white mb-2 uppercase tracking-tighter">Success!</h3>
                <p class="text-slate-400 text-sm mb-8">Your configuration has been updated successfully.</p>
                <button onclick="window.location.href='settings.php'" class="w-full bg-green-600 text-white py-3 rounded-lg font-bold text-sm uppercase shadow-lg shadow-green-900/20">Click OK</button>
            </div>
        </div>
    <?php endif; ?>

    <script src="js/main.js"></script>
    <script>
        function openModal(id) { document.getElementById(id).classList.remove('hidden'); }
        function closeModal(id) { document.getElementById(id).classList.add('hidden'); }
        function toggleSidebar() { 
            document.getElementById('sidebar').classList.toggle('-translate-x-full'); 
            document.getElementById('overlay').classList.toggle('hidden'); 
        }
    </script>
</body>
</html>