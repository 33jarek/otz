<?php include 'mvc.php'?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../assets/images/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="../assets/icons/remixicon.css">
    <link rel="stylesheet" href="../assets/styles/style.css">
    <title>Admin Panel</title>
</head>
<body>
    <nav id="nav-bar">
        <div class="format-menu">
            <div class="new-blocks">
                <p>Description blocks:</p>
                <div class="drop-element" id="new-line-elm" draggable="true" data-elm-name="new-line">New line</div>
                <div class="drop-element" id="list-elm" draggable="true" data-elm-name="list">- List</div>
                <div class="drop-element note" id="note-elm" draggable="true" data-elm-name="note" data-elm-class="note">Note</div>
                <div class="drop-element quote" id="quote-elm" draggable="true" data-elm-name="quote" data-elm-class="quote">Quote</div>
            </div>
            <div class="format-options">
                <p>Text formatting:</p>
                <div class="format-element link">Link</div>
                <div class="format-element t1">Tier 1</div>
                <div class="format-element t2">Tier 2</div>
                <div class="format-element t3">Tier 3</div>
                <div class="format-element object">Object</div>
                <div class="format-element mention">Mention</div>
                <div class="format-element token">Token</div>
            </div>
        </div>
        <ul>
            <li><button type="button" class="add-drop-item">+</button></li>
        </ul>
        <ul class="btns">
            <li><button type="button">Add new build</button></li>
            <li><button type="button">Remove Builds</button></li>
            <li><button type="button" class="selected-tab">Modify Perks</button></li>
        </ul>
    </nav>
    <form action="" method="POST" id="build-inserter" class="form-panel">
        <h1 class="heading">Insert new build</h1>
        <label>
            <p>Name:</p>
            <input type="text" name="build-name" autocomplete="off" required>
        </label>
        <label>
            <p>Character_type:</p>
            <select name="select-chars" id="select-chars" required>
                <option value="killer">Killer</option>
                <option value="survivor">Survivor</option>
            </select>
        </label>
        <label>
            <p>Character_id:</p>
            <select name="chars-and-names" id="chars-and-names" required>
                <?php c_returnSelectStruct('killer'); ?>
            </select>
        </label>
        <label>
            <p>Perk slot 1:</p>
            <input type="text" name="perk1" list="perksList" autocomplete="off" required>
        </label>
        <label>
            <p>Perk slot 2:</p>
            <input type="text" name="perk2" list="perksList" autocomplete="off" required>
        </label>
        <label>
            <p>Perk slot 3:</p>
            <input type="text" name="perk3" list="perksList" autocomplete="off" required>
        </label>
        <label>
            <p>Perk slot 4:</p>
            <input type="text" name="perk4" list="perksList" autocomplete="off" required>
        </label>
        <label>
            <p>Description:</p>
            <input type="text" name="build-desc" placeholder="add ', ' between each" autocomplete="off" required>
        </label>
        <input type="submit" name="s1" value="Add build" class="add-build-btn">
    </form>
    <form action="" method="POST" id="build-remover" class="form-panel">
        <h1 class="heading">Remove existing builds</h1>
        <label>
            <p>Select for:</p>
            <select name="builds-select" id="builds-select">
                <option value="killer">Killers</option>
                <option value="survivor">Survivors</option>
            </select>
        </label>
        <div id="build-table-holder">
            <table>
                <thead>
                    <tr>
                        <th>Character</th>
                        <th>Build Name</th>
                        <th>✖</th>
                    </tr>
                </thead>
                <tbody>
                    <?php c_returnTableStruct('killer'); ?>
                </tbody>
            </table>
        </div>
    </form>
    <form action="" method="POST" id="edit-perks" class="form-panel">
        <div class="form-wrapper">
            <h1>Modify perk details</h1>
            <label>
                <p>Select perk:</p>
                <select name="perk-details" id="perk-details">
                    <?php
                        $perk = c_returnPerksList();
                    ?>
                </select>
            </label>
            <label>
                <p>Name:</p>
                <input type="text" name="perk-name" id="perk-name" value="<?= htmlspecialchars($perk['name']); ?>" required>
            </label>
            <label>
                <p>Obtainment:</p>
                <input type="text" name="perk-obtainment" id="perk-obtainment" value="<?= htmlspecialchars($perk['obtainment']); ?>" required>
            </label>
            <div class="label">
                <p>Description:</p>
                <div class="drop-panel">

                </div>
            </div>
            <input type="hidden" name="perk-desc" id="perk-desc">
            <button type="submit" name="s2" class="change-perk-btn">Submit changes</button>
        </div>
        <div class="details-preview">
            <h3>This perk is obtained from -----</h3>
            <div class="perk-details">
                <div class="about-perk">
                    <h2>-----</h2>
                    <div class="details-holder">
                        
                    </div>
                </div>
                <div><img src="../assets/images/perk_icons/CorruptIntervention.png" alt="Corrupt Intervention icon"></div>
            </div>
        </div>
    </form>
    <div id="main-page-btn"><a href="../">Main page</a></div>
    <datalist id="perksList">
        <?php c_returnPerksList(true); ?>
    </datalist>

    <script src="/dbd/assets/modules/XMLHttpRequest/ajax_requests.js"></script>
    <script src="/dbd/assets/modules/admin_panel/show_tabs.js"></script>
    <script src="/dbd/assets/modules/admin_panel/drop_menu.js"></script>
    <script src="/dbd/assets/modules/admin_panel/preview_perk_details.js"></script>
    <script src="/dbd/assets/modules/admin_panel/text_format.js"></script>
    <script src="/dbd/assets/modules/admin_panel/desc_serializer.js"></script>
</body>
</html>