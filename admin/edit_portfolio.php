<?php
// admin/edit_portfolio.php
require_once 'auth_check.php';
require_once 'functions.php';

// 1. Get Project Data
if (!isset($_GET['id'])) redirect('portfolio.php');
$id = sanitize_input($_GET['id']);
$res = mysqli_query($conn, "SELECT * FROM {$TABLE_PREFIX}portfolio WHERE id = '$id'");
$project = mysqli_fetch_assoc($res);

if (!$project) redirect('portfolio.php');

$message = '';

// 2. Handle Update Logic
if (isset($_POST['update_project'])) {
    $title = sanitize_input($_POST['title']);
    $category = sanitize_input($_POST['category']);
    $desc = sanitize_input($_POST['description']);
    $image_url = $project['image_url']; // Default to old image

    // Check if a new image is being uploaded
    if (!empty($_FILES['image']['name'])) {
        $file_name = $_FILES['image']['name'];
        $tmp_name = $_FILES['image']['tmp_name'];
        $ext = pathinfo($file_name, PATHINFO_EXTENSION);
        $new_file_name = time() . "_" . rand(1000, 9999) . "." . $ext;

        if (move_uploaded_file($tmp_name, "../uploads/portfolio/" . $new_file_name)) {
            // Delete old file from server to save space
            if (file_exists("../uploads/portfolio/" . $project['image_url'])) {
                unlink("../uploads/portfolio/" . $project['image_url']);
            }
            $image_url = $new_file_name;
        }
    }

    $sql = "UPDATE {$TABLE_PREFIX}portfolio 
            SET title = '$title', category = '$category', description = '$desc', image_url = '$image_url' 
            WHERE id = '$id'";

    if (mysqli_query($conn, $sql)) {
        log_activity("Updated project: $title");
        redirect('portfolio.php?status=updated');
    } else {
        $message = "Error updating project.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Project | <?= $SETTINGS['company_name'] ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-edit text-primary"></i> Edit Project</h2>
                <a href="portfolio.php" class="btn btn-outline-secondary">Cancel</a>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <?php if($message): ?> <div class="alert alert-danger"><?= $message ?></div> <?php endif; ?>

                    <form method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Project Title</label>
                            <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($project['title']) ?>" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Category</label>
                                <select name="category" class="form-select">
                                    <option <?= $project['category'] == 'Web Development' ? 'selected' : '' ?>>Web Development</option>
                                    <option <?= $project['category'] == 'Web Design' ? 'selected' : '' ?>>Web Design</option>
                                    <option <?= $project['category'] == 'Graphic Design' ? 'selected' : '' ?>>Graphic Design</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Update Image <small class="text-muted">(Leave blank to keep current)</small></label>
                                <input type="file" name="image" class="form-control" accept="image/*">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label d-block fw-bold">Current Image</label>
                            <img src="../uploads/portfolio/<?= $project['image_url'] ?>" class="img-thumbnail" style="height: 150px;">
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Description</label>
                            <textarea name="description" class="form-control" rows="6"><?= htmlspecialchars($project['description']) ?></textarea>
                        </div>

                        <button type="submit" name="update_project" class="btn btn-primary w-100 py-2 fw-bold">
                            <i class="fas fa-save me-2"></i> Update Project Details
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>