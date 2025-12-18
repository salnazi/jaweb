<?php
/**
 * Filename: index.php
 * Logic: Removed Strictly Dynamic Width Badge CSS logic.
 * Logic Update: Dual buttons (Demo and Pay & Download) in card footer.
 * Rule: Final and complete code provided.
 */
require_once 'db_config.php';
require_once 'header.php'; 

$tp = $TABLE_PREFIX ?? 'jaweb_';

// 1. Logic: Fetch Categories for Sidebar
$cat_sql = "SELECT id, name FROM {$tp}categories ORDER BY name ASC";
$cat_query = mysqli_query($conn, $cat_sql);

// 2. Logic: Fetch Portfolio (JOIN p.category = c.name)
$search = $_GET['search'] ?? '';
$search_query = "";
if (!empty($search)) {
    $s = mysqli_real_escape_string($conn, $search);
    $search_query = " AND (p.title LIKE '%$s%' OR p.description LIKE '%$s%')";
}

$port_sql = "SELECT p.* FROM {$tp}portfolio AS p WHERE 1=1 $search_query ORDER BY p.id DESC";
$port_query = mysqli_query($conn, $port_sql);

/**
 * LOGIC: High-Contrast Dynamic Color Mapper
 */
function getStrictBadgeStyle($text) {
    $palette = [
        ['bg' => '#FFFDD0', 'text' => '#000000'], 
        ['bg' => '#000000', 'text' => '#ffffff'], 
        ['bg' => '#2563eb', 'text' => '#ffffff'], 
        ['bg' => '#059669', 'text' => '#ffffff'], 
        ['bg' => '#d97706', 'text' => '#ffffff'], 
        ['bg' => '#7c3aed', 'text' => '#ffffff'], 
        ['bg' => '#db2777', 'text' => '#ffffff']  
    ];
    $index = abs(crc32($text)) % count($palette);
    return $palette[$index];
}

function slugify($text) {
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    $text = trim($text, '-');
    $text = strtolower($text);
    return empty($text) ? 'n-a' : $text;
}
?>

<style>
/* Price Contrast logic */
.price-text {
    font-weight: 800;
    font-size: 1.5rem;
    display: block;
}
.light .price-text { color: #000000; }
.dark .price-text { color: #ffffff; }

/* Card Container logic */
.portfolio-card {
    height: 100%;
    display: flex;
    flex-direction: column;
    transition: transform 0.3s ease;
    border-radius: 3px;
}
.light .portfolio-card { background: #ffffff; border: 1px solid #efefef; }
.dark .portfolio-card { background: #161b22; border: 1px solid #30363d; }

.portfolio-card:hover {
    transform: translateY(-5px);
}

/* Badge Wrapper logic */
.badge-container {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

/* Button Row logic */
.action-buttons {
    display: flex;
    gap: 10px;
    width: 100%;
}
.action-buttons .button {
    flex: 1; 
    border-radius: 3px;
}
</style>

<section class="hero-custom">
    <div class="has-text-centered">
        <h1 class="title is-1 has-text-white is-uppercase mb-2">Our Portfolio</h1>
        <p class="subtitle is-5 has-text-info">Tailored Solutions for Modern Problems</p>
    </div>
</section>

<section class="section">
    <div class="container is-fluid px-6">
        <div class="columns is-variable is-8">
            
            <aside class="column is-3-desktop is-4-tablet">
                <div class="menu cat-sidebar">
                    <p class="menu-label has-text-weight-bold mb-4">Categories</p>
                    <ul class="menu-list">
                        <li>
                            <a href="#" class="filter-trigger is-active" data-filter="all">
                                <span class="icon-text">
                                    <span class="icon"><i class="fas fa-layer-group"></i></span>
                                    <span>All Projects</span>
                                </span>
                            </a>
                        </li>
                        <?php if ($cat_query): while($cat = mysqli_fetch_assoc($cat_query)): ?>
                            <li>
                                <a href="#" class="filter-trigger" data-filter="filter-<?= slugify($cat['name']) ?>">
                                    <?= htmlspecialchars($cat['name']) ?>
                                </a>
                            </li>
                        <?php endwhile; endif; ?>
                    </ul>
                </div>
            </aside>

            <main class="column is-9-desktop is-8-tablet">
                <div class="columns is-multiline" id="portfolio-grid">
                    <?php 
                    if ($port_query && mysqli_num_rows($port_query) > 0): 
                        while($row = mysqli_fetch_assoc($port_query)): 
                            $itemFilterClass = "filter-" . slugify($row['category']);
                            $catStyle = getStrictBadgeStyle($row['category']);
                    ?>
                        <div class="column is-4-desktop is-6-tablet portfolio-item <?= $itemFilterClass ?>">
                            <div class="card portfolio-card shadow-sm">
                                <div class="card-image">
                                    <figure class="image is-4by3">
                                        <img src="<?= htmlspecialchars($row['image_url']) ?>" alt="<?= htmlspecialchars($row['title']) ?>" class="p-4 object-cover">
                                    </figure>
                                </div>
                                <div class="card-content is-flex-grow-1">
                                    <div class="mb-4">
                                        <p class="title is-5 mb-3 dark:text-white"><?= htmlspecialchars($row['title']) ?></p>
                                        <div class="badge-container">
                                            <span class="tag" style="background-color: <?= $catStyle['bg'] ?>; color: <?= $catStyle['text'] ?>; border-radius: 3px;">
                                                <i class="fas fa-folder-open"></i>&nbsp;&nbsp;<?= htmlspecialchars($row['category']) ?>
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <?php if(!empty($row['price'])): ?>
                                        <div class="mb-3">
                                            <span class="price-text">
                                                ₹<?= number_format($row['price'], 0) ?>
                                            </span>
                                        </div>
                                    <?php endif; ?>

                                    <div class="content is-size-7 mb-4 dark:text-gray-400">
                                        <?= substr(strip_tags($row['description'] ?? ''), 0, 85) ?>...
                                    </div>
                                    
                                    <?php 
                                    if(!empty($row['seo_tag'])): 
                                        $tags = explode(',', $row['seo_tag']);
                                        echo '<div class="badge-container mb-4">';
                                        foreach($tags as $tag): 
                                            $trimmedTag = trim($tag);
                                            if($trimmedTag !== ''):
                                                $tagStyle = getStrictBadgeStyle($trimmedTag);
                                    ?>
                                        <span class="tag is-small" style="background-color: <?= $tagStyle['bg'] ?>; color: <?= $tagStyle['text'] ?>; border-radius: 3px;">
                                            <i class="fas fa-tag"></i>&nbsp;&nbsp;<?= htmlspecialchars($trimmedTag) ?>
                                        </span>
                                    <?php 
                                            endif;
                                        endforeach; 
                                        echo '</div>';
                                    endif; 
                                    ?>
                                    
                                    <footer class="pt-3 mt-auto border-t dark:border-gray-800">
                                        <div class="action-buttons">
                                            <a href="<?= htmlspecialchars($row['demo_url'] ?? '#') ?>" 
                                               class="button is-small is-link is-outlined has-text-weight-bold">
                                                Demo
                                            </a>
                                            <a href="checkout.php?id=<?= $row['id'] ?>" 
                                               class="button is-small is-primary has-text-weight-bold">
                                                Pay & Download
                                            </a>
                                        </div>
                                    </footer>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; else: ?>
                        <div class="column is-12 has-text-centered py-6">
                            <p class="is-size-5 has-text-grey">No projects found in this category.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </main>

        </div>
    </div>
</section>

<?php require_once 'footer.php'; ?>