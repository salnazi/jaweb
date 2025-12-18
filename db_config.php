<?php
// db_config.php - Core Database and Global Configuration (Rule 1, 19, 23)
/* --- PROJECT RULES ---
 * 1. Database config in db_config.php
 * 2. Always use mysqli_query($conn, $sql)
 * 19. All rules should be printed in coding itself
 * 23. Always use table creation with prefix ('jaweb_')
 */

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'jasquare_app');
define('TABLE_PREFIX', 'jaweb_');
$TABLE_PREFIX = TABLE_PREFIX;

define('ROLE_ADMIN', 'admin');
define('ROLE_EDITOR', 'editor');

date_default_timezone_set('Asia/Kolkata');

$conn = @mysqli_query(mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME), "SET NAMES utf8mb4");
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if (!$conn) {
    die("Database Connection Failed. Check db_config.php.");
}

// Fetch Global Settings
$SETTINGS = [];
$sql_settings = "SELECT setting_key, setting_value FROM {$TABLE_PREFIX}site_settings";
$result_settings = mysqli_query($conn, $sql_settings);
if ($result_settings) {
    while ($row = mysqli_fetch_assoc($result_settings)) {
        $SETTINGS[$row['setting_key']] = $row['setting_value'];
    }
}
?>