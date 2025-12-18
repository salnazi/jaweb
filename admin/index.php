<?php
session_start();
require_once 'functions.php';

if (isset($_SESSION['admin_logged_in'])) redirect('dashboard.php');

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // FIX: Only passing one argument to match your functions.php logic
    $username = sanitize_input($_POST['username']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM {$TABLE_PREFIX}users WHERE username = '$username' LIMIT 1";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        // ... inside your password_verify block in admin/index.php ...
        if (password_verify($password, $user['password'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_user_id']   = $user['id']; // ADD THIS LINE
            $_SESSION['admin_username']  = $user['username'];
            $_SESSION['admin_role']      = $user['role'];
            
            log_activity("Logged in");
            redirect('dashboard.php');
        } else { $error = "Invalid Credentials"; }
    } else { $error = "Invalid Credentials"; }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login | JAWeb</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body { background: linear-gradient(135deg, #1e1e2f, #2d2d44); height: 100vh; display: flex; align-items: center; justify-content: center; }
        .login-card { background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.1); border-radius: 15px; padding: 40px; width: 400px; color: white; }
        .form-control { background: rgba(0,0,0,0.2); border: 1px solid #444; color: white; }
        .btn-primary { background: #6c63ff; border: none; }
    </style>
</head>
<body>
    <div class="login-card">
        <h3 class="text-center mb-4">JAWeb Admin</h3>
        <?php if($error): ?> <div class="alert alert-danger p-2"><?= $error ?></div> <?php endif; ?>
        <form method="POST">
            <div class="mb-3"><label>Username</label><input type="text" name="username" class="form-control" required></div>
            <div class="mb-3"><label>Password</label><input type="password" name="password" class="form-control" required></div>
            <button type="submit" class="btn btn-primary w-100">Sign In</button>
        </form>
    </div>
</body>
</html>