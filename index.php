<?php include 'mvc.php'?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="assets/images/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="assets/styles/styles.css">
    <link rel="stylesheet" href="assets/icons/remixicon.css">
    <title>Otzdarva Builds for DBD</title>
</head>
<body>
    <header id="hdr">
        <div id="heading-section">
            <h1><span id="otz">Otzdarva</span> Builds for</h1>
            <div id="logo">
                <img src="assets/images/dbd-logo.webp" alt="dbd">
            </div>
            <p><span>Last update:</span> January 22, 2025</p>
        </div>
        <nav id="nav-bar">
            <ul id="categories-list">
                <li class="category-select is-killer">
                    <div id="killers-category">
                        <img src="assets/images/killer-icon.png" alt="Killers icon">
                        <h2>KILLERS</h2>
                    </div>
                </li>
                <li class="category-select is-survivor">
                    <div id="survivors-category">
                        <img src="assets/images/survivor-icon.png" alt="Survivor icon">
                        <h2>SURVIVORS</h2>
                    </div>
                </li>
            </ul>
        </nav>
    </header>
    <main id="main-content">
        <div id="heading-section">
            <h1><span id="otz">Otzdarva</span> Builds for</h1>
            <div id="logo">
                <img src="assets/images/dbd-logo.webp" alt="dbd">
            </div>
            <p><span>Last update:</span> January 22, 2025</p>
        </div>
        <button type="button" id="powrot">Go back</button>
        <div class="category-section" id="killers-category">
            <?php c_displayCharacters('killer'); ?>
        </div>
        <div class="category-section" id="survivors-category">
            <?php c_displayCharacters('survivor'); ?>
        </div>
    </main>
    <dialog class="perk-display">
        <form method="dialog" class="dialog-form">
            <button class="close-dialog-btn"><i class="ri-close-large-fill"></i></button>
            <div class="form-content">
            </div>
        </form>
    </dialog>
    <footer>
        
    </footer>
    <script src="/dbd/assets/animations/gsap/gsap.min.js"></script>
    <script src="/dbd/assets/animations/gsap/Observer.min.js"></script>

    <script src="/dbd/assets/modules/main_page/choose_category.js"></script>
    <script src="/dbd/assets/modules/main_page/show_perk_lists.js"></script>
    <script src="/dbd/assets/modules/main_page/back_to_menu.js"></script>
    <script src="/dbd/assets/modules/main_page/show_perk_name.js"></script>
    <script src="/dbd/assets/modules/XMLHttpRequest/ajax_requests.js"></script>

    <!-- UNCOMMENT TO TUNR OFF CATEGORY SELECT -->
    <!-- <script>
        gsap.set(hdr, {
            clipPath: 'polygon(0 0, 100% 0, 100% 0%, 0 0%)'
        });
    </script> -->
</body>
</html>