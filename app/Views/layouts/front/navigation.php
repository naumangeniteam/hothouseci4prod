<?php

// Load required models
use App\Models\CommonModel;
use CodeIgniter\I18n\Time;

// Load CommonModel
$commonModel = new CommonModel();

// Load language file
$lang = service('language');
$lang->setLocale('en'); // Change 'en' to your preferred language


// Fetch settings
$where = ["is_active" => '1'];
$settings = $commonModel->getData('multiple', 'setting_tbl', $where);
?>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Get all dropdown toggles
        const dropdownToggles = document.querySelectorAll('.dropdown-toggle');

        // Remove any existing click handlers that might conflict
        dropdownToggles.forEach(toggle => {
            toggle.removeAttribute('data-bs-toggle');
            toggle.removeAttribute('data-toggle');
        });

        // Add click event listener to each dropdown toggle
        dropdownToggles.forEach(toggle => {
            toggle.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation(); // Stop event bubbling

                // Find the parent dropdown
                const parent = this.parentNode;

                // Find the dropdown menu
                const dropdownMenu = parent.querySelector('.dropdown-menu');

                // Close all other dropdowns first
                document.querySelectorAll('.dropdown-menu').forEach(menu => {
                    if (menu !== dropdownMenu && menu.classList.contains('show')) {
                        menu.classList.remove('show');
                    }
                });

                // Toggle the 'show' class
                dropdownMenu.classList.toggle('show');
            });
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.dropdown')) {
                document.querySelectorAll('.dropdown-menu.show').forEach(menu => {
                    menu.classList.remove('show');
                });
            }
        });
    });
</script>


<header>
    <div class="container-fluid">
        <div class="site-nav">
            <a class="navbar-brand" href="<?= base_url('home') ?>">
                <img src="<?= $ASSET_FRONT_URL ?>img/banner/logo.svg" class="img-fluid" alt="Logo">
            </a>
            <nav class="navbar navbar-expand-lg">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon">
                        <img src="<?= $ASSET_FRONT_URL ?>icons/menu.svg" alt="menu">
                    </span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav">
                        <li class="nav-item"><a class="nav-link" href="<?= base_url() ?>">Home</a></li>
                        <li class="nav-item dropdown">
                            <a class="dropdown-toggle" href="#" id="calendarDropdown" role="button">
                                Calendar
                            </a>
                            <div class="dropdown-menu" aria-labelledby="calendarDropdown">
                                <a class="dropdown-item" href="<?= base_url('calendar') ?>">Calendar</a>
                                <a class="dropdown-item" href="<?= base_url('venue') ?>">Venues</a>
                                <a class="dropdown-item" href="<?= base_url('festivals') ?>">Festivals</a>
                                <a class="dropdown-item" href="<?= base_url('calendar') ?>">Free Concerts</a>
                                <a class="dropdown-item" href="https://mags.hothousejazzmagazine.com/af033db61d.html">Listings Summary</a>
                            </div>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="https://hothousejazz.beehiiv.com/">Newsletter</a></li>
                        <li class="nav-item"><a class="nav-link" target="_blank" href="https://hothousejazz.net/">Videos & Podcasts</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">HHJ Store</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?= base_url('contact_us') ?>">Contact Us</a></li>
                        <li class="nav-item nav-user-login">
                            <a class="nav-link" href="https://simplecirc.com/subscriber_login/hot-house-jazz-guide">
                                <img class="mb-hide" src="<?= $ASSET_FRONT_URL ?>icons/user.svg" alt="User Login">
                                <img class="desk-hide" src="<?= $ASSET_FRONT_URL ?>icons/user-white.svg" alt="User Login"> Login
                            </a>
                        </li>
                        <li class="nav-item"><a class="nav-link nav-submit-event" href="<?= base_url('submit_event') ?>">Submit Events</a></li>
                        <li class="nav-item ml-xl-2 ml-lg-0"><a class="nav-link nav-submit-event" href="<?= base_url('submit_festival') ?>">Submit Festivals</a></li>
                    </ul>
                </div>
            </nav>
        </div>
    </div>
</header>