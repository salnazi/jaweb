<?php
require_once 'db_config.php';

// 1.. Fetch Categories for the Filter Bar
$cat_query = mysqli_query($conn, "SELECT DISTINCT category FROM {$TABLE_PREFIX}portfolio");

// 2. Fetch Projects for the Grid
$portfolio_result = mysqli_query($conn, "SELECT * FROM {$TABLE_PREFIX}portfolio ORDER BY created_at DESC");

// 3. Fetch Dynamic Menu Items
$nav_query = mysqli_query($conn, "SELECT * FROM {$TABLE_PREFIX}menus WHERE status = 1 ORDER BY sort_order ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($SETTINGS['company_name']) ?> | Premium Portfolio</title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    
    <style>
        :root { --primary-color: #6c63ff; --dark-bg: #1e1e2f; }
        body { font-family: 'Inter', sans-serif; scroll-behavior: smooth; }
        
        .hero { 
            background: linear-gradient(rgba(30,30,47,0.8), rgba(30,30,47,0.8)), url('https://images.unsplash.com/photo-1497215728101-856f4ea42174?auto=format&fit=crop&w=1350&q=80');
            background-size: cover; padding: 160px 0; color: white; clip-path: polygon(0 0, 100% 0, 100% 90%, 0% 100%);
        }

        /* Portfolio Styling */
        .filter-btn { border-radius: 30px; padding: 8px 20px; margin: 5px; transition: 0.3s; }
        .portfolio-card { border: none; border-radius: 15px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.08); transition: 0.4s; }
        .portfolio-card:hover { transform: translateY(-10px); }
        .portfolio-img { height: 250px; object-fit: cover; }
        
        .section-title::after { content: ''; display: block; width: 50px; height: 3px; background: var(--primary-color); margin: 15px auto; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php"><?= htmlspecialchars($SETTINGS['company_name']) ?></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <?php while($nav = mysqli_fetch_assoc($nav_query)): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= $nav['link'] ?>"><?= htmlspecialchars($nav['label']) ?></a>
                    </li>
                <?php endwhile; ?>
                <li class="nav-item"><a class="nav-link btn btn-primary text-white ms-lg-3 px-4" href="admin/">Login</a></li>
            </ul>
        </div>
    </div>
</nav>

<header class="hero text-center animate__animated animate__fadeIn">
    <div class="container">
        <h1 class="display-3 fw-bold mb-3">Creative Digital Agency</h1>
        <p class="lead mb-4">Located in <?= htmlspecialchars($SETTINGS['company_address']) ?></p>
        <a href="#portfolio" class="btn btn-primary btn-lg px-5 shadow-lg">Our Work</a>
    </div>
</header>

<section class="py-5" id="portfolio">
    <div class="container">
        <h2 class="text-center section-title">Portfolio Showcase</h2>
        
        <div class="text-center my-4">
            <button class="btn btn-outline-primary filter-btn active" data-filter="all">All</button>
            <?php 
            mysqli_data_seek($cat_query, 0); // Reset pointer
            while($cat = mysqli_fetch_assoc($cat_query)): 
                $slug = strtolower(str_replace(' ', '-', $cat['category']));
            ?>
                <button class="btn btn-outline-primary filter-btn" data-filter="<?= $slug ?>">
                    <?= htmlspecialchars($cat['category']) ?>
                </button>
            <?php endwhile; ?>
        </div>

        <div class="row g-4" id="portfolio-grid">
            <?php while($proj = mysqli_fetch_assoc($portfolio_result)): 
                $proj_cat = strtolower(str_replace(' ', '-', $proj['category']));
            ?>
            <div class="col-md-4 portfolio-item <?= $proj_cat ?>">
                <div class="card portfolio-card h-100 animate__animated">
                    <img src="uploads/portfolio/<?= $proj['image_url'] ?>" class="card-img-top portfolio-img" alt="<?= $proj['title'] ?>">
                    <div class="card-body">
                        <span class="badge bg-light text-primary mb-2"><?= $proj['category'] ?></span>
                        <h5 class="card-title fw-bold"><?= htmlspecialchars($proj['title']) ?></h5>
                        <a href="portfolio-details.php?id=<?= $proj['id'] ?>" class="stretched-link"></a>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>



<section class="bg-light py-5" id="contact">
    <div class="container py-5">
        <div class="row g-5">
            <div class="col-lg-5">
                <h2 class="section-title text-start">Start a Project</h2>
                <p class="text-muted">Tell us about your needs and we'll get back to you within 24 hours.</p>
                <div class="mt-4">
                    <div class="d-flex mb-3">
                        <div class="btn btn-primary btn-sm rounded-circle me-3"><i class="fas fa-phone"></i></div>
                        <span><?= $SETTINGS['company_phone'] ?></span>
                    </div>
                    <div class="d-flex">
                        <div class="btn btn-primary btn-sm rounded-circle me-3"><i class="fas fa-envelope"></i></div>
                        <span><?= $SETTINGS['company_email'] ?></span>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm p-4">
                    <form action="contact-process.php" method="POST">
                        <div class="row g-3">
                            <div class="col-md-6"><input type="text" name="name" class="form-control" placeholder="Your Name" required></div>
                            <div class="col-md-6"><input type="email" name="email" class="form-control" placeholder="Email Address" required></div>
                            <div class="col-12">
                                <select name="service" class="form-select">
                                    <option>Web Development</option>
                                    <option>UI/UX Design</option>
                                    <option>Digital Marketing</option>
                                </select>
                            </div>
                            <div class="col-12"><textarea name="message" class="form-control" rows="5" placeholder="Project Details" required></textarea></div>
                            <div class="col-12"><button type="submit" class="btn btn-dark w-100 py-3">Send Inquiry</button></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<footer class="bg-dark text-white py-4 text-center">
    <p class="mb-0">&copy; <?= date('Y') ?> <?= $SETTINGS['company_name'] ?>. All rights reserved.</p>
</footer>

<script>
document.querySelectorAll('.filter-btn').forEach(button => {
    button.addEventListener('click', function() {
        // Handle Active Button State
        document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('active', 'btn-primary', 'text-white'));
        this.classList.add('active', 'btn-primary', 'text-white');

        const filterValue = this.getAttribute('data-filter');
        const items = document.querySelectorAll('.portfolio-item');

        items.forEach(item => {
            const card = item.querySelector('.portfolio-card');
            if (filterValue === 'all' || item.classList.contains(filterValue)) {
                item.style.display = 'block';
                card.classList.add('animate__fadeIn');
            } else {
                item.style.display = 'none';
                card.classList.remove('animate__fadeIn');
            }
        });
    });
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>