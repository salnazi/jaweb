<?php
// admin/add_portfolio.php
require_once 'auth_check.php';
require_once 'functions.php';

$message = '';
$message_type = 'danger';

// 1. Handle Form Submission
if (isset($_POST['save_project'])) {
    // Sanitize text inputs
    $title = sanitize_input($_POST['title']);
    $category = sanitize_input($_POST['category']);
    $description = sanitize_input($_POST['description']);

    // 2. Validate Image Upload
    if (!empty($_FILES['image']['name'])) {
        $file_name = $_FILES['image']['name'];
        $tmp_name = $_FILES['image']['tmp_name'];
        $file_size = $_FILES['image']['size'];
        $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_ext = array("jpg", "jpeg", "png", "webp", "gif");

        if (in_array($ext, $allowed_ext)) {
            if ($file_size < 5000000) { // Limit: 5MB
                // Generate unique filename
                $new_file_name = time() . "_" . rand(1000, 9999) . "." . $ext;
                $upload_dir = "../uploads/portfolio/";
                
                // Ensure directory exists
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                }

                if (move_uploaded_file($tmp_name, $upload_dir . $new_file_name)) {
                    // 3. Insert into Database
                    $sql = "INSERT INTO {$TABLE_PREFIX}portfolio (title, category, description, image_url) 
                            VALUES ('$title', '$category', '$description', '$new_file_name')";
                    
                    if (mysqli_query($conn, $sql)) {
                        log_activity("Added project: $title");
                        redirect('portfolio.php?status=added');
                    } else {
                        $message = "Database Error: Could not save record.";
                    }
                } else {
                    $message = "Upload Error: Failed to move file to server.";
                }
            } else {
                $message = "File is too large. Maximum size is 5MB.";
            }
        } else {
            $message = "Invalid file type. Allowed: JPG, PNG, WEBP, GIF.";
        }
    } else {
        $message = "Please select a project image.";
    }
}

// 4. Fetch Categories for Dropdown
$cat_list = mysqli_query($conn, "SELECT * FROM {$TABLE_PREFIX}categories ORDER BY name ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Project | Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background: #f4f7f6; }
        .card { border-radius: 12px; }
        .form-label { font-weight: 600; color: #444; }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold"><i class="fas fa-plus-circle text-primary me-2"></i>New Portfolio Entry</h2>
                <a href="portfolio.php" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Back to List
                </a>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <?php if($message): ?>
                        <div class="alert alert-<?= $message_type ?> alert-dismissible fade show" role="alert">
                            <?= $message ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <form method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label">Project Title</label>
                            <input type="text" name="title" class="form-control form-control-lg" placeholder="e.g. Corporate Brand Identity" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Category</label>
                                <select name="category" class="form-select" required>
                                    <option value="">Select Category...</option>
                                    <?php while($c = mysqli_fetch_assoc($cat_list)): ?>
                                        <option value="<?= $c['name'] ?>"><?= $c['name'] ?></option>
                                    <?php endwhile; ?>
                                </select>
                                <small class="text-muted">Manage these in <a href="categories.php">Category Manager</a></small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Featured Image</label>
                                <input type="file" name="image" class="form-control" accept="image/*" required>
                                <small class="text-muted">Recommended: 1200x800px (JPG/PNG)</small>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Project Description</label>
                            <textarea name="description" class="form-control" rows="6" placeholder="Describe the work done, technologies used, and project results..."></textarea>
                        </div>

                        <hr class="my-4">

                        <div class="d-grid">
                            <button type="submit" name="save_project" class="btn btn-primary btn-lg fw-bold shadow">
                                <i class="fas fa-paper-plane me-2"></i> Publish to Portfolio
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <p class="text-center mt-4 text-muted small">
                All uploads are stored in <code>/uploads/portfolio/</code>
            </p>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>