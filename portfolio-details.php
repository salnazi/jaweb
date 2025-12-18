<?php
// portfolio-details.php
require_once 'db_config.php';

// 1. Validate the Project ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$project_id = mysqli_real_escape_string($conn, $_GET['id']);

// 2. Fetch Project Data
$sql = "SELECT * FROM {$TABLE_PREFIX}portfolio WHERE id = '$project_id' LIMIT 1";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) === 0) {
    header("Location: index.php");
    exit;
}

$project = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($project['title']) ?> | <?= $SETTINGS['company_name'] ?></title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        .details-hero { 
            background: #f8f9fa; 
            padding: 80px 0; 
            border-bottom: 1px solid #eee;
        }
        .project-main-img { 
            width: 100%; 
            border-radius: 20px; 
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        .sidebar-box {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            position: sticky;
            top: 100px;
        }
        .meta-label { font-weight: bold; color: #6c63ff; font-size: 0.8rem; text-transform: uppercase; }
        .meta-value { margin-bottom: 20px; color: #333; }
    </style>
</head>
<body>

<nav class="navbar navbar-light bg-white border-bottom py-3">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">
            <i class="fas fa-arrow-left me-2"></i> <?= $SETTINGS['company_name'] ?>
        </a>
    </div>
</nav>

<div class="details-hero">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <span class="badge bg-primary mb-2"><?= $project['category'] ?></span>
                <h1 class="display-4 fw-bold"><?= htmlspecialchars($project['title']) ?></h1>
            </div>
            <div class="col-md-4 text-md-end">
                <a href="index.php#contact" class="btn btn-dark btn-lg px-4">Inquire About This</a>
            </div>
        </div>
    </div>
</div>



<div class="container py-5">
    <div class="row">
        <div class="col-lg-8">
            <img src="uploads/portfolio/<?= $project['image_url'] ?>" class="project-main-img" alt="<?= $project['title'] ?>">
            
            <h3 class="fw-bold mb-4">Project Overview</h3>
            <div class="project-description lead text-muted" style="white-space: pre-line;">
                <?= htmlspecialchars($project['description']) ?>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="sidebar-box">
                <h5 class="fw-bold mb-4">Project Details</h5>
                
                <div class="meta-label">Category</div>
                <div class="meta-value"><?= $project['category'] ?></div>
                
                <div class="meta-label">Project Date</div>
                <div class="meta-value"><?= date('F Y', strtotime($project['created_at'])) ?></div>
                
                <div class="meta-label">Client</div>
                <div class="meta-value">Private Client / Internal</div>
                
                <hr>
                
                <p class="small text-muted mb-4">Interested in a similar project for your business? Get a free quote today.</p>
                <a href="index.php#contact" class="btn btn-outline-primary w-100">Start Project</a>
            </div>
        </div>
    </div>
</div>
<?php
// Fetch Related Projects (Same category, excluding current project)
$cat = mysqli_real_escape_string($conn, $project['category']);
$current_id = $project['id'];

$related_sql = "SELECT * FROM {$TABLE_PREFIX}portfolio 
                WHERE category = '$cat' AND id != '$current_id' 
                ORDER BY RAND() LIMIT 3";
$related_result = mysqli_query($conn, $related_sql);
?>

<section class="py-5 bg-light border-top">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <h3 class="fw-bold mb-0">More in <?= htmlspecialchars($cat) ?></h3>
                <p class="text-muted mb-0">Discover similar work we've done.</p>
            </div>
            <a href="index.php#portfolio" class="btn btn-outline-dark btn-sm">View All Work</a>
        </div>

        <div class="row g-4">
            <?php if(mysqli_num_rows($related_result) > 0): ?>
                <?php while($rel = mysqli_fetch_assoc($related_result)): ?>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; overflow: hidden;">
                        <img src="uploads/portfolio/<?= $rel['image_url'] ?>" class="card-img-top" style="height: 200px; object-fit: cover;" alt="<?= $rel['title'] ?>">
                        <div class="card-body">
                            <h6 class="fw-bold mb-1"><?= htmlspecialchars($rel['title']) ?></h6>
                            <a href="portfolio-details.php?id=<?= $rel['id'] ?>" class="stretched-link small text-primary text-decoration-none">
                                View Case Study <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12 text-center py-4">
                    <p class="text-muted italic">Check out our other creative solutions on the homepage.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
<footer class="bg-dark text-white py-4 mt-5 text-center">
    <p class="mb-0">&copy; <?= date('Y') ?> <?= $SETTINGS['company_name'] ?>. All rights reserved.</p>
</footer>

</body>
</html>