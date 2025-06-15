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
    <form action="" method="POST" id="build-inserter">
        <h1>Insert new build</h1>
        <label>Name: 
            <input type="text" name="build-name" autocomplete="off" required>
        </label>
        <label>Character_type:
            <select name="select-chars" id="select-chars" required>
                <option value="killer">Killer</option>
                <option value="survivor">Survivor</option>
            </select>
        </label>
        <label>Character_id:
            <select name="chars-and-names" id="chars-and-names" required>
                <?php c_returnSelectStruct('killer'); ?>
            </select>
        </label>
        <label>Perk slot 1:
            <input type="text" name="perk1" list="perksList" required>
        </label>
        <label>Perk slot 2:
            <input type="text" name="perk2" list="perksList" required>
        </label>
        <label>Perk slot 3:
            <input type="text" name="perk3" list="perksList" required>
        </label>
        <label>Perk slot 4:
            <input type="text" name="perk4" list="perksList" required>
        </label>
        <label>Description:
            <input type="text" name="build-desc" placeholder="add ', ' between each" autocomplete="off" required>
        </label>
        <input type="submit" name="s1" value="Add build">
    </form>
    <form action="" method="POST" id="build-remover">
        <h1>Remove existing build</h1>
        <label>Select for:
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
    <div id="main-page-btn"><a href="../index.php">Main page</a></div>
    <datalist id="perksList">
        <?php c_returnPerksList(); ?>
    </datalist>

    <script src="js/ajax_requests.js"></script>
</body>
</html>