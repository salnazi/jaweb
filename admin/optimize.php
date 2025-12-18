<?php
require_once 'auth_check.php';
require_once 'functions.php';

if (!has_role(ROLE_ADMIN)) {
    redirect('dashboard.php?error=unauthorized');
}

// 1. Get all tables with your prefix
$tables_result = mysqli_query($conn, "SHOW TABLES LIKE '{$TABLE_PREFIX}%'");
$optimized_tables = [];

while ($row = mysqli_fetch_row($tables_result)) {
    $table = $row[0];
    // 2. Run Optimize command
    mysqli_query($conn, "OPTIMIZE TABLE $table");
    $optimized_tables[] = $table;
}

// 3. Log the action
$count = count($optimized_tables);
log_activity("Database Optimized: $count tables defragmented.");

// 4. Redirect back with success message
redirect('settings.php?msg=optimized');