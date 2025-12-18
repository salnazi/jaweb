<?php
/**
 * Filename: admin/functions.php
 * Last Update: 2025-12-17
 * Rules No: 3, 8, 11
 * Details: Core utility functions. Fixed activity_log table mapping and auto-module detection.
 */

require_once '../db_config.php';

define('ADMIN_ASSETS_URL', 'assets');

/**
 * Sanitize input data to prevent SQL Injection and XSS
 */
function sanitize_input($data) {
    global $conn;
    if (is_null($data)) {
        return "";
    }
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return mysqli_real_escape_string($conn, $data);
}

/**
 * Standard Header Redirect
 */
function redirect($url) {
    header("Location: " . $url);
    exit;
}

/**
 * Check if the logged-in user has the required permission level
 */
function has_role($required_role) {
    if (!isset($_SESSION['admin_role'])) {
        return false;
    }
    if ($_SESSION['admin_role'] === ROLE_ADMIN) {
        return true;
    }
    return $_SESSION['admin_role'] === $required_role;
}

/**
 * Log System Activity
 * Automatically captures the current file (module) and maps to prefix_activity_log
 * Fields: id, user, action_type, module, action, created_at
 */
function log_activity($action, $action_type = 'UPDATE') {
    global $conn;
    
    // Correct table name per instruction (singular)
    $table_logs = TABLE_PREFIX . 'activity_log';
    
    // Auto-detect the filename (e.g., settings.php, categories.php)
    $module = basename($_SERVER['PHP_SELF']);
    $user   = $_SESSION['admin_username'] ?? 'System';
    
    // Sanitize values for insertion
    $safe_user   = mysqli_real_escape_string($conn, $user);
    $safe_action = mysqli_real_escape_string($conn, $action);
    $safe_type   = mysqli_real_escape_string($conn, strtoupper($action_type));
    $safe_module = mysqli_real_escape_string($conn, $module);

    $sql = "INSERT INTO $table_logs (user, action_type, module, action, created_at) 
            VALUES ('$safe_user', '$safe_type', '$safe_module', '$safe_action', NOW())";
    
    mysqli_query($conn, $sql);
}

/**
 * Format timestamp for consistent UI display
 */
function format_system_date($date) {
    return date('d M Y, h:i A', strtotime($date));
}
?>