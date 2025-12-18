<?php
/**
 * Filename: footer.php
 * Logic: Slim Bulma-styled footer with dynamic settings, categories, and WhatsApp toggle.
 * Update: Integrated dynamic company_about from settings.
 */

// 1. Fetch settings from jaweb_site_settings
$settings_sql = "SELECT setting_key, setting_value FROM jaweb_site_settings";
$settings_query = mysqli_query($conn, $settings_sql);
$SITE_DATA = [];

if ($settings_query) {
    while ($row = mysqli_fetch_assoc($settings_query)) {
        $SITE_DATA[$row['setting_key']] = $row['setting_value'];
    }
}

// Map dynamic variables
$c_name      = $SITE_DATA['company_name'] ?? 'JA SQUARE';
$c_email     = $SITE_DATA['company_email'] ?? 'contact@jafmarketplace.in';
$c_contact   = $SITE_DATA['company_phone'] ?? '+91 84283 57459';
$c_about     = $SITE_DATA['company_about'] ?? 'Your premier partner for digital solutions and creative web design.';

// Clean phone number for WhatsApp Link (removes spaces/plus)
$wa_number = preg_replace('/[^0-9]/', '', $c_contact);

// Split Brand Logic
$name_parts  = explode(' ', trim($c_name));
$first_part  = $name_parts[0] ?? 'JA';
$second_part = $name_parts[1] ?? 'SQUARE';

// 2. Fetch categories (id, name, slug)
$cat_sql = "SELECT id, name, slug FROM jaweb_categories ORDER BY name ASC";
$cat_query = mysqli_query($conn, $cat_sql);
?>

    <footer class="footer bg-white dark:bg-[#0b0e14] border-t border-gray-200 dark:border-gray-800 transition-colors duration-300 py-4">
        <div class="container px-6">
            <div class="columns is-multiline is-variable is-2">
                
                <div class="column is-4 mb-0">
                    <h2 class="title is-5 mb-2 dark:text-white uppercase">
                        <?= htmlspecialchars($first_part) ?> <span class="has-text-info"><?= htmlspecialchars($second_part) ?> <sup>2</sup></span>
                    </h2>
                    <p class="is-size-7 text-black dark:text-gray-400">
                        <?= htmlspecialchars($c_about) ?>
                    </p>
                </div>

                <div class="column is-4 mb-0">
                    <h3 class="subtitle is-7 has-text-weight-bold is-uppercase dark:text-gray-300 mb-2">Categories</h3>
                    <ul class="is-size-7">
                        <?php if ($cat_query && mysqli_num_rows($cat_query) > 0): ?>
                            <div class="columns is-mobile is-multiline is-gapless">
                            <?php while($cat = mysqli_fetch_assoc($cat_query)): ?>
                                <li class="column is-6 mb-1">
                                    <a href="category.php?s=<?= htmlspecialchars($cat['slug']) ?>" class="text-black dark:text-gray-400 hover:text-info">
                                        <?= htmlspecialchars($cat['name']) ?>
                                    </a>
                                </li>
                            <?php endwhile; ?>
                            </div>
                        <?php else: ?>
                            <li class="has-text-grey-light">No categories available.</li>
                        <?php endif; ?>
                    </ul>
                </div>

                <div class="column is-4 mb-0">
                    <h3 class="subtitle is-7 has-text-weight-bold is-uppercase dark:text-gray-300 mb-2">Connect</h3>
                    <div class="flex items-center justify-between bg-gray-50 dark:bg-gray-800 p-2 rounded-lg border border-gray-100 dark:border-gray-700">
                        <div class="is-size-7">
                            <p class="font-bold text-success">Online Support</p>
                            <p class="text-xs text-gray-500">Chat with us now</p>
                        </div>
                        <a href="https://wa.me/<?= $wa_number ?>" target="_blank" class="button is-success is-small is-rounded shadow-sm">
                            <span class="icon"><i class="fab fa-whatsapp"></i></span>
                            <span>WhatsApp</span>
                        </a>
                    </div>
                    
                    <div class="is-size-7 text-black dark:text-gray-400 mt-3">
                        <p><strong>Email:</strong> <?= htmlspecialchars($c_email) ?></p>
                        <p><strong>Phone:</strong> <?= htmlspecialchars($c_contact) ?></p>
                    </div>
                </div>

            </div>

            <hr class="has-background-grey-lighter dark:has-background-grey-darker my-3">

            <div class="level is-mobile mb-0">
                <div class="level-left">
                    <div class="level-item is-size-7 text-black dark:text-gray-500">
                        &copy; <?= date('Y') ?> <?= htmlspecialchars($c_name) ?>
                    </div>
                </div>
                <div class="level-right">
                    <div class="level-item is-size-7 text-black dark:text-gray-500">
                        Powered By : <?= htmlspecialchars($c_name) ?>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    <script src="assets/js/main.js"></script>

</body>
</html>