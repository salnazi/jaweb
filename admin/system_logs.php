<?php
/**
 * Filename: admin/system_logs.php
 * Note: This file follows Rule 17 - Change Log is stored in DB.
 * Rules No: 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17
 */

require_once 'auth_check.php';
require_once 'functions.php';

$db_name = "jasquare_app";
$table_prefix = "jaweb_";

// --- PAGINATION LOGIC (Rule 12) ---
$limit = 10; 
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$total_results = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM {$table_prefix}system_logs"))['total'];
$total_pages = ceil($total_results / $limit);

// --- FETCH LOGS ---
$logs = mysqli_query($conn, "SELECT * FROM {$table_prefix}system_logs ORDER BY updated_at DESC LIMIT $offset, $limit");

// --- DELETE LOG LOGIC ---
if (isset($_GET['delete_id'])) {
    $id = (int)$_GET['delete_id'];
    mysqli_query($conn, "DELETE FROM {$table_prefix}system_logs WHERE id = $id");
    $_SESSION['success_msg'] = "Log entry removed.";
    header("Location: system_logs.php"); exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Logs | Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --accent: #00d2ff; --bg-dark: #0f172a; --card-dark: #1e293b; --sidebar-dark: #020617; }
        body { background: var(--bg-dark); color: #e2e8f0; font-family: 'Inter', sans-serif; }
        
        .sidebar { width: 250px; background: var(--sidebar-dark); min-height: 100vh; position: fixed; transition: 0.3s; z-index: 1000; border-right: 1px solid #334155; }
        .sidebar a { color: #94a3b8; text-decoration: none; padding: 15px 25px; display: block; transition: 0.3s; }
        .sidebar a:hover, .sidebar a.active { color: var(--accent); background: rgba(0,210,255,0.05); border-right: 3px solid var(--accent); }
        
        .top-header { background: var(--sidebar-dark); padding: 12px 30px; border-bottom: 1px solid #334155; }
        .user-welcome { font-size: 0.9rem; color: #fff; }
        .current-time { font-size: 0.85rem; color: var(--accent); background: rgba(0,210,255,0.1); padding: 4px 12px; border-radius: 20px; }

        .main-content { margin-left: 250px; width: calc(100% - 250px); transition: 0.3s; }
        .table-container { background: var(--card-dark); border-radius: 12px; border: 1px solid #334155; overflow: hidden; }
        .table { color: #f1f5f9; margin-bottom: 0; font-size: 0.9rem; }
        .table thead { background: #334155; color: var(--accent); font-size: 0.75rem; text-transform: uppercase; }
        .table tbody tr { border-bottom: 1px solid #334155; }
        
        .search-box { position: relative; width: 300px; }
        .search-box input { background: #0f172a; border: 1px solid #334155; color: #fff; border-radius: 8px; padding-left: 35px; }
        .search-box i { position: absolute; left: 12px; top: 10px; color: #94a3b8; }

        .btn-circle { border-radius: 50%; width: 32px; height: 32px; padding: 0; display: inline-flex; align-items: center; justify-content: center; }

        @media (max-width: 768px) {
            .sidebar { margin-left: -250px; }
            .sidebar.active { margin-left: 0; }
            .main-content { margin-left: 0; width: 100%; }
        }
    </style>
</head>
<body>

<div class="d-flex">
    <div class="sidebar" id="sidebar">
        <div class="p-4 text-center text-white"><h4 class="fw-bold">JA<span class="text-info">Web</span></h4></div>
        <a href="dashboard.php"><i class="fas fa-home me-2"></i> Dashboard</a>
        <a href="portfolio.php"><i class="fas fa-images me-2"></i> Portfolio</a>
        <a href="categories.php"><i class="fas fa-tags me-2"></i> Categories</a>
        <a href="system_logs.php" class="active"><i class="fas fa-history me-2"></i> System Logs</a>
        <a href="settings.php"><i class="fas fa-cog me-2"></i> Settings</a>
    </div>

    <div class="main-content">
        <div class="top-header d-flex justify-content-between align-items-center">
            <button class="btn btn-dark d-md-none" onclick="document.getElementById('sidebar').classList.toggle('active')">
                <i class="fas fa-bars"></i>
            </button>
            <div class="ms-auto d-flex align-items-center">
                <span class="user-welcome me-3">Welcome, <strong>Admin</strong></span>
                <span class="current-time me-4"><i class="far fa-clock me-1"></i> <?= date('h:i A') ?></span>
                <a href="logout.php" class="btn btn-sm btn-danger px-3 rounded-pill">Logout</a>
            </div>
        </div>

        <div class="p-4">
            <a href="dashboard.php" class="btn btn-outline-secondary btn-sm mb-4"><i class="fas fa-arrow-left me-1"></i> Back</a>

            <h3 class="fw-bold text-white mb-4">System Activity & Rule Logs</h3>

            <div class="table-container shadow-lg">
                <div class="p-3 d-flex justify-content-between align-items-center border-bottom border-secondary">
                    <span class="text-accent small fw-bold">VERSION HISTORY</span>
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" id="liveSearch" class="form-control" placeholder="Search logs...">
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle" id="logTable">
                        <thead>
                            <tr>
                                <th class="ps-4">Timestamp</th>
                                <th>File Name</th>
                                <th>Rules</th>
                                <th>Change Log</th>
                                <th class="text-end pe-4">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = mysqli_fetch_assoc($logs)): ?>
                            <tr>
                                <td class="ps-4 text-muted small"><?= $row['updated_at'] ?></td>
                                <td class="text-info fw-bold"><?= $row['filename'] ?></td>
                                <td><span class="badge bg-dark text-accent border border-info">Rule <?= $row['rules_no'] ?></span></td>
                                <td class="small text-muted"><?= substr($row['change_log'], 0, 80) ?>...</td>
                                <td class="text-end pe-4">
                                    <button class="btn btn-circle btn-success me-1" onclick="previewLog(<?= htmlspecialchars(json_encode($row)) ?>)"><i class="fas fa-eye fa-xs"></i></button>
                                    <button class="btn btn-circle btn-danger" onclick="confirmDelete('system_logs.php?delete_id=<?= $row['id'] ?>')"><i class="fas fa-trash fa-xs"></i></button>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                <div class="p-3 bg-dark d-flex justify-content-end">
                    <ul class="pagination pagination-sm m-0">
                        <?php for($i=1; $i<=$total_pages; $i++): ?>
                            <li class="page-item <?= ($page == $i) ? 'active' : '' ?>">
                                <a class="page-link bg-dark border-secondary text-white" href="system_logs.php?page=<?= $i ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="previewModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content bg-dark text-white border-secondary">
            <div class="modal-header border-secondary">
                <h5 class="modal-title">Log Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-6"><strong>File:</strong> <span id="p_file" class="text-info"></span></div>
                    <div class="col-6 text-end"><span id="p_time" class="text-muted small"></span></div>
                </div>
                <hr class="border-secondary">
                <h6 class="text-accent fw-bold">Applied Rules:</h6>
                <p id="p_rules" class="bg-black p-2 rounded small border border-secondary"></p>
                <h6 class="text-accent fw-bold mt-4">Change Log:</h6>
                <div id="p_log" class="bg-black p-3 rounded border border-secondary" style="white-space: pre-wrap;"></div>
            </div>
            <div class="modal-footer border-secondary">
                <button class="btn btn-success px-5 rounded-pill" data-bs-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark text-center p-4 border-secondary shadow-lg">
            <i class="fas fa-trash-alt fa-3x text-danger mb-3"></i>
            <h4 class="text-white">Delete Entry?</h4>
            <p class="text-muted">This log record will be permanently removed.</p>
            <div class="mt-4">
                <button class="btn btn-danger me-2 px-4" data-bs-dismiss="modal">Cancel</button>
                <a id="delLink" class="btn btn-success px-4">OK</a>
            </div>
        </div>
    </div>
</div>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Live Search on Keyin (Rule 15)
    document.getElementById('liveSearch').addEventListener('keyup', function() {
        let filter = this.value.toUpperCase();
        let rows = document.querySelector("#logTable tbody").rows;
        for (let i = 0; i < rows.length; i++) {
            let text = rows[i].textContent.toUpperCase();
            rows[i].style.display = text.indexOf(filter) > -1 ? "" : "none";
        }
    });

    function previewLog(data) {
        document.getElementById('p_file').innerText = data.filename;
        document.getElementById('p_time').innerText = data.updated_at;
        document.getElementById('p_rules').innerText = "Rules: " + data.rules_no + "\n" + data.rules_details;
        document.getElementById('p_log').innerText = data.change_log;
        new bootstrap.Modal(document.getElementById('previewModal')).show();
    }

    function confirmDelete(url) {
        document.getElementById('delLink').href = url;
        new bootstrap.Modal(document.getElementById('deleteModal')).show();
    }
</script>
</body>
</html>