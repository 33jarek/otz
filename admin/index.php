<?php include 'mvc.php'?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../content/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="style.css">
    <title>Document</title>
</head>
<body>
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
        <h1 class="heading">Remove existing build</h1>
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
                        <th>id</th>
                        <th>character</th>
                        <th>build name</th>
                        <th>action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php c_returnTableStruct('killer'); ?>
                </tbody>
            </table>
        </div>
    </form>
    <div id="main-page-btn"><a href="../">Main page</a></div>
    <datalist id="perksList">
        <?php c_returnPerksList(); ?>
    </datalist>

    <script src="js/ajax_requests.js"></script>
</body>
</html>