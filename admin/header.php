<?php
/**
 * Filename: admin/header.php
 * Last Update: 2025-12-17
 * Rules No: 5, 8, 9
 * Details: Global header. Contains branding, navigation, and global table variables.
 */
require_once '../db_config.php';
require_once 'auth_check.php';
require_once 'functions.php';

$current_page = basename($_SERVER['PHP_SELF']);

// Global Table Variables
$table_settings = TABLE_PREFIX . 'site_settings';
$table_leads    = TABLE_PREFIX . 'leads';
$table_logs     = TABLE_PREFIX . 'activity_log';
$table_users    = TABLE_PREFIX . 'users';

// Fetch Dynamic Branding
$company_res  = mysqli_query($conn, "SELECT setting_value FROM $table_settings WHERE setting_key='company_name' LIMIT 1");
$company_data = mysqli_fetch_assoc($company_res);
$display_name = $company_data['setting_value'] ?? 'JA Square';

function isActive($page, $current) {
    return $page === $current ? 'bg-sky-600 text-white font-bold border-l-4 border-sky-400 shadow-lg shadow-sky-900/20' : 'text-slate-400 hover:bg-slate-800 transition-colors';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title><?= htmlspecialchars($display_name) ?> | Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="flex min-h-screen overflow-hidden bg-slate-950 text-slate-300">
    <div id="overlay" onclick="toggleSidebar()" class="fixed inset-0 bg-black/80 z-40 hidden lg:hidden"></div>
    <aside id="sidebar" class="fixed lg:static inset-y-0 left-0 w-64 bg-slate-900 border-r border-slate-800 z-50 flex flex-col transition-transform duration-300 -translate-x-full lg:translate-x-0">
        <div class="p-6 border-b border-slate-800">
            <h1 class="text-xl font-black text-white uppercase tracking-tighter"><?= htmlspecialchars($display_name) ?></h1>
        </div>
        <nav class="flex-1 p-0 py-4 space-y-1 overflow-y-auto">
            <a href="dashboard.php" class="flex items-center gap-3 px-6 py-3 <?= isActive('dashboard.php', $current_page) ?>"><i class="fa-solid fa-gauge-high w-4"></i> Dashboards</a>
            <a href="leads.php" class="flex items-center gap-3 px-6 py-3 <?= isActive('leads.php', $current_page) ?>"><i class="fa-solid fa-envelope-open-text w-4"></i> Leads</a>
            <a href="categories.php" class="flex items-center gap-3 px-6 py-3 <?= isActive('categories.php', $current_page) ?>"><i class="fa-solid fa-tags w-4"></i> Categories</a>
            <a href="portfolio.php" class="flex items-center gap-3 px-6 py-3 <?= isActive('portfolio.php', $current_page) ?>"><i class="fa-solid fa-briefcase w-4"></i> Portfolio</a>
            <a href="menus.php" class="flex items-center gap-3 px-6 py-3 <?= isActive('menus.php', $current_page) ?>"><i class="fa-solid fa-bars w-4"></i> Menus</a>
            <a href="settings.php" class="flex items-center gap-3 px-6 py-3 <?= isActive('settings.php', $current_page) ?>"><i class="fa-solid fa-gear w-4"></i> Settings</a>
            <a href="users.php" class="flex items-center gap-3 px-6 py-3 <?= isActive('users.php', $current_page) ?>"><i class="fa-solid fa-user-shield w-4"></i> Users</a>
        </nav>
        <div class="p-4 bg-slate-950 border-t border-slate-800 text-center text-[10px] font-bold text-slate-500 uppercase">System Ready</div>
    </aside>
    <main class="flex-1 flex flex-col overflow-hidden">
        <header class="h-16 bg-slate-900 border-b border-slate-800 flex items-center justify-between px-6 shrink-0">
            <div class="flex items-center gap-4">
                <button onclick="toggleSidebar()" class="lg:hidden text-slate-400 hover:text-white transition-colors"><i class="fa-solid fa-bars-staggered text-xl"></i></button>
                <div class="hidden sm:flex items-center bg-slate-950 border border-slate-800 rounded-full px-4 py-1.5 gap-3">
                    <span class="text-[10px] font-black text-sky-500 uppercase tracking-widest">Welcome, Admin</span>
                    <div class="w-[1px] h-3 bg-slate-800"></div>
                    <span id="headerClock" class="text-xs font-mono font-bold text-white tracking-tighter">00:00:00</span>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <a href="logout.php" class="bg-red-600/10 hover:bg-red-600 text-red-500 hover:text-white px-4 py-2 rounded-lg text-xs font-bold uppercase transition-all flex items-center gap-2"><i class="fa-solid fa-power-off text-[10px]"></i> Logout</a>
            </div>
        </header>