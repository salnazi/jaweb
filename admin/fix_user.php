<?php
require_once 'functions.php';

// Clear old admin
mysqli_query($conn, "DELETE FROM {$TABLE_PREFIX}users WHERE username = 'admin'");

// Create fresh admin with hashed password
$username = 'admin';
$password = password_hash('admin123', PASSWORD_BCRYPT);
$role = 'admin';

$sql = "INSERT INTO {$TABLE_PREFIX}users (username, password, role) VALUES ('$username', '$password', '$role')";

if(mysqli_query($conn, $sql)) {
    echo "Admin user reset successfully! Try logging in now.";
} else {
    echo "Error: " . mysqli_error($conn);
}
?>