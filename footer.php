<?php
/**
 * Filename: footer.php
 * Logic: Bulma-styled footer with dynamic copyright and JS links.
 * Rule: Final and complete code provided.
 */
?>

    <footer class="footer bg-white dark:bg-[#0b0e14] border-t border-gray-200 dark:border-gray-800 transition-colors duration-300">
        <div class="container px-6">
            <div class="columns is-multiline">
                
                <div class="column is-4">
                    <h2 class="title is-4 mb-4 dark:text-white">
                        JA<span class="has-text-info">SQUARE</span>
                    </h2>
                    <p class="is-size-6 text-black dark:text-gray-400">
                        Crafting premium digital portfolios and web solutions. 
                        We combine design with technology to elevate your brand.
                    </p>
                </div>

                <div class="column is-4">
                    <h3 class="subtitle is-6 has-text-weight-bold is-uppercase dark:text-gray-300">Quick Navigation</h3>
                    <ul class="is-size-7">
                        <li class="mb-2"><a href="index.php" class="text-black dark:text-gray-400 hover:text-info">Portfolio Home</a></li>
                        <li class="mb-2"><a href="#" class="text-black dark:text-gray-400 hover:text-info">Service Packages</a></li>
                        <li class="mb-2"><a href="#" class="text-black dark:text-gray-400 hover:text-info">Our Team</a></li>
                        <li class="mb-2"><a href="#" class="text-black dark:text-gray-400 hover:text-info">Contact Us</a></li>
                    </ul>
                </div>

                <div class="column is-4">
                    <h3 class="subtitle is-6 has-text-weight-bold is-uppercase dark:text-gray-300">Connect With Us</h3>
                    <div class="buttons">
                        <a href="#" class="button is-light is-rounded"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="button is-light is-rounded"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="button is-light is-rounded"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#" class="button is-light is-rounded"><i class="fab fa-x-twitter"></i></a>
                    </div>
                    <p class="is-size-7 text-black dark:text-gray-400 mt-2">
                        Email: info@jasquare.com<br>
                        Support available 24/7.
                    </p>
                </div>

            </div>

            <hr class="has-background-grey-lighter dark:has-background-grey-darker my-6">

            <div class="level pb-6">
                <div class="level-left">
                    <div class="level-item is-size-7 text-black dark:text-gray-500">
                        &copy; <?= date('Y') ?> JA SQUARE. All rights reserved.
                    </div>
                </div>
                <div class="level-right">
                    <div class="level-item is-size-7 text-black dark:text-gray-500">
                        Designed with Bulma & Tailwind
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    
    <script src="assets/js/main.js"></script>

    <style>
        .portfolio-item {
            transition: transform 0.3s ease, opacity 0.3s ease;
        }
    </style>

</body>
</html>