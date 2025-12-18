<?php
/**
 * Filename: index.php
 * Logic: Removed Top Search/Sort.
 * Logic Update: High-Gloss 3D button effects for Demo (Blue) and Pay & Download (Green).
 * Sidebar Update: 5 categories initial view, 5 more on click, includes live category search.
 * Rule: Final and complete code provided.
 * Update [2025-12-18]: Integrated dynamic Hero Image/Text & removed mb-5 header.
 */
require_once 'db_config.php';
require_once 'header.php'; 

$tp = $TABLE_PREFIX ?? 'jaweb_';

// Logic: Fetch Hero dynamic settings
$hero_img  = !empty($SETTINGS['hero_image']) ? 'img/' . $SETTINGS['hero_image'] : 'img/default_hero.jpg';
$hero_text = $SETTINGS['hero_text'] ?? 'Tailored Solutions for Modern Problems';

// 1. Logic: Fetch Categories for Sidebar
$cat_sql = "SELECT id, name FROM {$tp}categories ORDER BY name ASC";
$cat_query = mysqli_query($conn, $cat_sql);

// 2. Logic: Fetch Portfolio (Default Descending)
$port_sql = "SELECT p.* FROM {$tp}portfolio AS p ORDER BY p.id DESC";
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
/* Hero Image Styling */
.hero-custom {
    background: linear-gradient(rgba(11, 14, 20, 0.8), rgba(11, 14, 20, 0.8)), url('<?= $hero_img ?>');
    background-size: cover;
    background-position: center;
    padding: 100px 0;
}

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
.portfolio-card:hover { transform: translateY(-5px); }

/* Sidebar Search logic */
.sidebar-search-container { margin-bottom: 15px; }
.sidebar-search-input { font-size: 0.85rem; border-radius: 3px !important; }

/* Badge Wrapper logic */
.badge-container { display: flex; flex-wrap: wrap; gap: 8px; }

/* --- GLOSSY BUTTON LOGIC --- */
.action-buttons { display: flex; gap: 10px; width: 100%; }
.action-buttons .button { 
    flex: 1; 
    border-radius: 3px; 
    border: none; 
    box-shadow: inset 0 1px 0 rgba(255,255,255,0.4), 0 2px 4px rgba(0,0,0,0.2);
    text-shadow: 0 1px 1px rgba(0,0,0,0.3);
    transition: all 0.2s ease;
}
.action-buttons .button:active {
    box-shadow: inset 0 2px 4px rgba(0,0,0,0.2);
    transform: translateY(1px);
}

/* Glossy Blue (Demo) */
.button.is-demo-blue { 
    background: linear-gradient(180deg, #4facfe 0%, #0061ff 100%) !important;
    color: #ffffff !important; 
}
.button.is-demo-blue:hover {
    background: linear-gradient(180deg, #60b5ff 0%, #1a75ff 100%) !important;
}

/* Glossy Green (Pay & Download) */
.button.is-download-green { 
    background: linear-gradient(180deg, #22c55e 0%, #15803d 100%) !important;
    color: #ffffff !important; 
}
.button.is-download-green:hover {
    background: linear-gradient(180deg, #4ade80 0%, #16a34a 100%) !important;
}

/* Sidebar Category Logic */
.cat-item.is-hidden-load, .cat-item.is-filtered-out { display: none; }
#show-more-cats { cursor: pointer; font-weight: 600; font-size: 0.85rem; padding: 10px; display: inline-block; color: #2563eb; }
</style>

<section class="hero-custom">
    <div class="has-text-centered">
        <h1 class="title is-1 has-text-white is-uppercase mb-2">
            <?= htmlspecialchars($first_part) ?> <span class="has-text-info"><?= htmlspecialchars($second_part) ?></span>
        </h1>
        <p class="subtitle is-5 has-text-info"><?= nl2br(htmlspecialchars($hero_text)) ?></p>
    </div>
</section>

<section class="section">
    <div class="container is-fluid px-6">
        <div class="columns is-variable is-8">
            
            <aside class="column is-3-desktop is-4-tablet">
                <div class="menu cat-sidebar">
                    <p class="menu-label has-text-weight-bold mb-2">Categories</p>
                    
                    <div class="sidebar-search-container control has-icons-left">
                        <input class="input sidebar-search-input" type="text" id="cat-search-box" placeholder="Find category...">
                        <span class="icon is-small is-left"><i class="fas fa-filter"></i></span>
                    </div>

                    <ul class="menu-list" id="category-list">
                        <li>
                            <a href="#" class="filter-trigger is-active" data-filter="all">
                                <span class="icon-text">
                                    <span class="icon"><i class="fas fa-layer-group"></i></span>
                                    <span>All Projects</span>
                                </span>
                            </a>
                        </li>
                        <?php 
                        $counter = 0;
                        if ($cat_query): while($cat = mysqli_fetch_assoc($cat_query)): 
                            $counter++;
                            $hiddenClass = ($counter > 5) ? 'is-hidden-load' : '';
                        ?>
                            <li class="cat-item <?= $hiddenClass ?>">
                                <a href="#" class="filter-trigger" data-filter="filter-<?= slugify($cat['name']) ?>">
                                    <?= htmlspecialchars($cat['name']) ?>
                                </a>
                            </li>
                        <?php endwhile; endif; ?>
                    </ul>
                    <?php if ($counter > 5): ?>
                        <a id="show-more-cats"><i class="fas fa-plus-circle mr-1"></i> Show More</a>
                    <?php endif; ?>
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
                                            <span class="price-text">₹<?= number_format($row['price'], 0) ?></span>
                                        </div>
                                    <?php endif; ?>

                                    <div class="content is-size-7 mb-4 dark:text-gray-400">
                                        <?= substr(strip_tags($row['description'] ?? ''), 0, 85) ?>...
                                    </div>
                                    
                                    <footer class="pt-3 mt-auto border-t dark:border-gray-800">
                                        <div class="action-buttons">
                                            <a href="<?= htmlspecialchars($row['demo_url'] ?? '#') ?>" class="button is-small is-demo-blue has-text-weight-bold">Demo</a>
                                            <a href="checkout.php?id=<?= $row['id'] ?>" class="button is-small is-download-green has-text-weight-bold">Pay & Download</a>
                                        </div>
                                    </footer>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; else: ?>
                        <div class="column is-12 has-text-centered py-6">
                            <p class="is-size-5 has-text-grey">No projects found.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </main>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const showMoreBtn = document.getElementById('show-more-cats');
    const catSearchBox = document.getElementById('cat-search-box');
    const catItems = document.querySelectorAll('.cat-item');

    catSearchBox.addEventListener('input', function() {
        const filterText = this.value.toLowerCase();
        if (filterText.length > 0) {
            if (showMoreBtn) showMoreBtn.style.display = 'none';
            catItems.forEach(item => {
                const text = item.textContent.toLowerCase();
                if (text.includes(filterText)) {
                    item.classList.remove('is-hidden-load', 'is-filtered-out');
                } else {
                    item.classList.add('is-filtered-out');
                }
            });
        } else {
            if (showMoreBtn) showMoreBtn.style.display = 'inline-block';
            catItems.forEach((item, index) => {
                item.classList.remove('is-filtered-out');
                if (index >= 5) {
                    item.classList.add('is-hidden-load');
                }
            });
        }
    });

    if (showMoreBtn) {
        showMoreBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const hiddenItems = document.querySelectorAll('.cat-item.is-hidden-load');
            for (let i = 0; i < 5; i++) {
                if (hiddenItems[i]) hiddenItems[i].classList.remove('is-hidden-load');
            }
            if (document.querySelectorAll('.cat-item.is-hidden-load').length === 0) {
                showMoreBtn.style.display = 'none';
            }
        });
    }
});
</script>

<?php require_once 'footer.php'; ?>