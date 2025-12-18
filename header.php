<?php
/**
 * Filename: header.php
 * Logic: Amazon-style layout (Logo -> Search -> Menu -> Theme Toggle)
 * Features: Sticky Header + Full-Width 300px Hero Section
 */
require_once 'db_config.php';
$tp = $TABLE_PREFIX;

// Logic: Fetch dynamic menu items
$nav_sql = "SELECT title, url FROM {$tp}menus WHERE is_active = 1 ORDER BY link_id ASC";
$nav_query = mysqli_query($conn, $nav_sql);
?>
<!DOCTYPE html>
<html lang="en" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($SETTINGS['company_name'] ?? 'JA Square') ?></title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@1.0.0/css/bulma.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: { 
                extend: { 
                    colors: { 
                        darkHeader: '#0b0e14', 
                        amazonOrange: '#febd69', 
                        tealInfo: '#00f2fe' 
                    } 
                } 
            }
        }
    </script>
    <style>
        .is-sticky-header {
            position: sticky;
            top: 0;
            z-index: 1000;
            background-color: #0b0e14;
        }

        /* Amazon Search Styling */
        .amazon-search-container {
            display: flex;
            flex-grow: 1;
            max-width: 700px;
            margin: 0 30px;
        }
        .amazon-search-input {
            border-radius: 3px 0 0 3px !important;
            height: 40px !important;
            border: none !important;
            font-size: 0.95rem !important;
            background-color: #ffffff !important;
            color: #000 !important;
        }
        .amazon-search-btn {
            background-color: #febd69 !important;
            border-radius: 0 3px 3px 0 !important;
            height: 40px !important;
            width: 50px;
            border: none !important;
            color: #333 !important;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Full Width 300px Hero Styling */
        .hero-custom {
            width: 100%;
            height: 300px;
            background: linear-gradient(rgba(11, 14, 20, 0.7), rgba(11, 14, 20, 0.7)), 
                        url('https://images.unsplash.com/photo-1451187580459-43490279c0fa?ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .nav-menu-link {
            transition: color 0.2s;
            white-space: nowrap;
        }

        body { transition: background-color 0.3s, color 0.3s; }
    </style>
</head>
<body class="bg-white text-black dark:bg-[#0b0e14] dark:text-white">

<header class="is-sticky-header shadow-md">
    <nav class="flex items-center justify-between px-6 py-3">
        
        <div class="flex-shrink-0">
            <a class="is-size-3 has-text-weight-bold has-text-white" href="index.php">
                JA<span class="has-text-info">SQUARE</span>
            </a>
        </div>

        <div class="amazon-search-container">
            <form action="index.php" method="GET" class="flex w-full">
                <input type="text" name="search" 
                       class="input amazon-search-input w-full" 
                       placeholder="Search projects..." 
                       value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                <button type="submit" class="button amazon-search-btn">
                    <span class="icon"><i class="fa fa-search"></i></span>
                </button>
            </form>
        </div>

        <div class="flex items-center gap-2">
            <div class="flex items-center gap-1 is-hidden-mobile">
                <?php if ($nav_query): while($nav = mysqli_fetch_assoc($nav_query)): ?>
                    <a class="nav-menu-link px-4 has-text-white is-size-7 is-uppercase has-text-weight-semibold hover:text-tealInfo" 
                       href="<?= htmlspecialchars($nav['url']) ?>">
                        <?= htmlspecialchars($nav['title']) ?>
                    </a>
                <?php endwhile; endif; ?>
            </div>

            <button id="themeToggle" class="button is-ghost has-text-white p-2 shadow-none border-none">
                <span class="icon">
                    <i class="fa-solid fa-moon is-size-5" id="themeIcon"></i>
                </span>
            </button>
        </div>
    </nav>
</header>

<section class="hero-custom">
    <div class="px-6">
        <h1 class="title is-1 has-text-white is-uppercase mb-2" style="letter-spacing: 2px;">Our Portfolio</h1>
        <p class="subtitle is-5 has-text-info font-semibold">Tailored Solutions for Modern Problems</p>
    </div>
</section>

<script>
    const themeToggle = document.getElementById('themeToggle');
    const themeIcon = document.getElementById('themeIcon');
    const htmlTag = document.documentElement;

    const setTheme = (isDark) => {
        if (isDark) {
            htmlTag.classList.add('dark');
            themeIcon.classList.replace('fa-moon', 'fa-sun');
            localStorage.setItem('theme', 'dark');
        } else {
            htmlTag.classList.remove('dark');
            themeIcon.classList.replace('fa-sun', 'fa-moon');
            localStorage.setItem('theme', 'light');
        }
    };

    themeToggle.addEventListener('click', () => {
        const isDark = !htmlTag.classList.contains('dark');
        setTheme(isDark);
    });

    if (localStorage.getItem('theme') === 'dark') {
        setTheme(true);
    }
</script>