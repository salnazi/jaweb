<?php
/**
 * admin/logout.php
 * Clears admin session and redirects to login
 */

// 1. Initialize the session
session_start();

// 2. Unset all session variables
$_SESSION = array();

// 3. Destroy the session cookie if it exists
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time()-42000, '/');
}

// 4. Destroy the session on the server
session_destroy();

// 5. Redirect to the login page
header("Location: index.php");
exit;