<?php
require_once 'auth_check.php';
require_once 'functions.php';

// 1. Security check - Only Admins can export data
if (!has_role(ROLE_ADMIN)) {
    die("Unauthorized access.");
}

// 2. Fetch all leads
$sql = "SELECT id, name, email, service, message, created_at FROM {$TABLE_PREFIX}leads ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    $filename = "leads_backup_" . date('Y-m-d') . ".csv";
    
    // 3. Set headers to force download
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=' . $filename);
    
    // 4. Open PHP output stream
    $output = fopen('php://output', 'w');
    
    // 5. Insert CSV Column Headers
    fputcsv($output, array('ID', 'Client Name', 'Email Address', 'Service Requested', 'Message', 'Date Received'));
    
    // 6. Loop through database and write to CSV
    while ($row = mysqli_fetch_assoc($result)) {
        fputcsv($output, $row);
    }
    
    fclose($output);
    log_activity("Exported all leads to CSV backup");
    exit;
} else {
    redirect('leads.php?error=no_data');
}