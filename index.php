<?php include 'mvc.php'?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="content/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="styles/styles.css">
    <link rel="stylesheet" href="icons/remixicon.css">
    <title>Otzdarva Builds for DBD</title>
</head>
<body>
    <header id="hdr">
        <div id="heading-section">
            <h1><span id="otz">Otzdarva</span> Builds for</h1>
            <div id="logo">
                <img src="content/imgs/dbd-logo.webp" alt="dbd">
            </div>
            <p><span>Last update:</span> January 22, 2025</p>
        </div>
        <nav id="nav-bar">
            <ul id="categories-list">
                <li class="category-select is-killer">
                    <div id="killers-category">
                        <img src="content/imgs/killer-icon.png" alt="Killers icon">
                        <h2>KILLERS</h2>
                    </div>
                </li>
                <li class="category-select is-survivor">
                    <div id="survivors-category">
                        <img src="content/imgs/survivor-icon.png" alt="Survivor icon">
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
                <img src="content/imgs/dbd-logo.webp" alt="dbd">
            </div>
            <p><span>Last update:</span> January 22, 2025</p>
        </div>
        <button type="button" id="powrot">POWRÓT JAK COŚ</button>
        <div class="category-section" id="killers-category">
            <?php c_getKillers(); ?>
        </div>
        <div class="category-section" id="survivors-category">
            <?php c_getSurvs(); ?>
        </div>
    </main>
    <footer>
        
    </footer>
    <script src="js/gsap/gsap.min.js"></script>

    <script>
        const btns = document.querySelectorAll('#categories-list .category-select');
        const hdr = document.querySelector('#hdr');
        const categories = document.querySelectorAll('.category-section');

        btns.forEach((btn, i) => {
            btn.addEventListener('click', () => {
                gsap.to(hdr, {
                    clipPath: 'polygon(0 0, 100% 0, 100% 0%, 0 0%)'
                });
                categories.forEach((category, j) => {
                    i === j ? category.style.display = 'grid' : category.style.display = 'none';
                });
            });
        });

        // gsap.set(hdr, {
        //     clipPath: 'polygon(0 0, 100% 0, 100% 0%, 0 0%)'
        // });

        const showFullListBtns = document.querySelectorAll('.btn-show-list');
        const fullLists = document.querySelectorAll('.builds-list');

        showFullListBtns.forEach((btn, i) => {
            btn.addEventListener('click', () => {
                fullLists[i].classList.add('shown');
            });
        });

        const powrot = document.querySelector('#powrot');
        powrot.addEventListener('click', () => {
            gsap.to(hdr, {
                clipPath: 'polygon(0 0, 100% 0, 100% 100%, 0 100%)',
            })
        });
    </script>
</body>
</html>