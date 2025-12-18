<?php
/**
 * Filename: header.php
 * Logic: Bulma Framework Implementation + Amazon Search + Logo with Centered Tagline
 * Finalized: Dec 18, 2025
 */
require_once 'db_config.php';
$tp = $TABLE_PREFIX;

// Fetch Navigation Menu
$nav_sql = "SELECT title, url FROM {$tp}menus WHERE is_active = 1 ORDER BY link_id ASC";
$nav_query = mysqli_query($conn, $nav_sql);

// Map dynamic variables from site_settings (company_name and company_tagline)
$c_full_name = $SETTINGS['company_name'] ?? 'JA SQUARE';
$c_tagline   = $SETTINGS['company_tagline'] ?? 'Complete Web Solution';

// Split Brand Logic for Logo styling (First word white, second word teal with superscript)
$name_parts  = explode(' ', trim($c_full_name), 2);
$first_part  = $name_parts[0] ?? 'JA';
$second_part = $name_parts[1] ?? 'SQUARE';
?>
<!DOCTYPE html>
<html lang="en" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($c_full_name) ?> | <?= htmlspecialchars($c_tagline) ?></title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@1.0.0/css/bulma.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: { 
                extend: { 
                    colors: { 
                        darkHeader: '#0b0e14', 
                        tealInfo: '#00f2fe'
                    } 
                } 
            }
        }
    </script>
    <style>
        .is-sticky-top {
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        /* --- Amazon-Style Search Bar Styles --- */
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
            background-color: #2563eb !important; 
            border-radius: 0 3px 3px 0 !important;
            height: 40px !important;
            width: 50px;
            border: none !important;
            color: #ffffff !important;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            transition: background 0.2s;
        }
        .amazon-search-btn:hover {
            background-color: #1d4ed8 !important;
        }

        /* Centered Tagline Logic */
        .brand-container {
            display: inline-flex;
            flex-direction: column;
            line-height: 1.1;
        }
        .tagline {
            display: block;
            text-align: center;
            font-style: italic;
            font-size: 0.7rem;
            letter-spacing: 0.5px;
            margin-top: 2px;
            color: #9ca3af;
            text-transform: uppercase;
        }
    </style>
</head>
<body class="bg-white text-black dark:bg-[#0b0e14] dark:text-white transition-colors duration-300">

<header class="is-sticky-top shadow-md">
    <nav class="flex items-center justify-between bg-darkHeader px-6 py-2 mb-0">
        
        <div class="flex-shrink-0">
            <a href="index.php" class="brand-container">
                <span class="is-size-3 has-text-weight-bold has-text-white uppercase tracking-tighter">
                    <?= htmlspecialchars($first_part) ?> 
                    <?php if ($second_part): ?>
                        <span class="has-text-info">
                            <?= htmlspecialchars($second_part) ?> <sup>2</sup>
                        </span>
                    <?php endif; ?>
                </span>
                <small class="tagline"><?= htmlspecialchars($c_tagline) ?></small>
            </a>
        </div>

        <div class="amazon-search-container">
            <form action="index.php" method="GET" class="flex w-full">
                <input type="text" name="search" 
                       class="input amazon-search-input w-full" 
                       placeholder="Search products, projects and more..." 
                       value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                <button type="submit" class="button amazon-search-btn">
                    <span class="icon"><i class="fa fa-search"></i></span>
                </button>
            </form>
        </div>

        <div class="flex items-center">
            <div class="is-hidden-mobile">
                <?php if ($nav_query): while($nav = mysqli_fetch_assoc($nav_query)): ?>
                    <a class="px-4 has-text-white is-size-7 is-uppercase has-text-weight-semibold hover:text-tealInfo transition-colors" href="<?= htmlspecialchars($nav['url']) ?>">
                        <?= htmlspecialchars($nav['title']) ?>
                    </a>
                <?php endwhile; endif; ?>
            </div>
        </div>

    </nav>
</header>