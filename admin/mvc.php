<?php $conn = mysqli_connect('localhost', 'root', '', 'dbd-db');

    // MODEL
    function m_returnSelectStruct($type) {
        global $conn;

        $allowed = ['killer', 'survivor'];
        if (!in_array($type, $allowed)) return '';

        $sql = "SELECT id, name FROM {$type}s";
        $result = mysqli_query($conn, $sql);
        return generateOptions($result);
    }
    function m_returnPerksList() {
        global $conn;

        $sql = "SELECT id, name FROM perks";
        $result = mysqli_query($conn, $sql);
        return generateDatalist($result);
    }
    function m_returnTableStruct($type) {
        global $conn;

        $allowed = ['killer', 'survivor'];
        if (!in_array($type, $allowed)) return '';

        $sql = "
            SELECT builds.id AS build_id, {$type}s.name AS {$type}_name, builds.name AS build_name FROM builds
            INNER JOIN {$type}s ON {$type}s.id = builds.character_id
            WHERE builds.character_type = '$type';
        ";
        $result = mysqli_query($conn, $sql);
        return generateTableLines($result);
    }
    function m_insertBuild() {
        global $conn;

        $buildName = $_POST['build-name'];
        $char      = $_POST['select-chars'];
        $charId    = $_POST['chars-and-names'];
        $desc      = $_POST['build-desc'];

        $perksList = m_getPerksArray();
        $perks     = [];

        for($i = 1; $i <= 4; $i++) {
            $perkName = $_POST["perk$i"];
            $index = array_search($perkName, $perksList);
            if($index === false) {
                echo "Slot{$i}: Perk \"{$perkName}\" does not exist!";
                return;
            }
            $perks[$i] = ($index+1); // +1 bo potrzebny jest index 1-n, a array liczy od 0
        }
        $sql = "
            INSERT INTO
            builds (name, character_type, character_id, slot1, slot2, slot3, slot4, description)
            VALUES ('{$buildName}', '{$char}', {$charId}, {$perks[1]}, {$perks[2]}, {$perks[3]}, {$perks[4]}, '{$desc}');
        ";
        $result = mysqli_query($conn, $sql);

        header('Location: ?');
        exit;
    }
    function m_getPerksArray() {
        global $conn;

        $perks = [];

        $sql = "SELECT name FROM perks";
        $result = mysqli_query($conn, $sql);
        while ($row = mysqli_fetch_assoc($result)) {
            $perks[] = $row['name'];
        }
        return $perks;
    }
    function m_deleteBuild() {
        global $conn;

        $id = intval($_GET['del']);

        $sql = "DELETE FROM builds WHERE id = $id";
        $result = mysqli_query($conn, $sql);

        $sql = "ALTER TABLE builds AUTO_INCREMENT = 1";
        $result = mysqli_query($conn, $sql);

        header('Location: ?');
        exit;
    }

    // VIEW
    function generateOptions($result) {
        $options = '';
        while($row = mysqli_fetch_assoc($result)) {
            $options .= "<option value=\"{$row['id']}\">{$row['name']}</option>";
        }
        return $options;
    }
    function generateDatalist($result) {
        $options = '';
        while($row = mysqli_fetch_assoc($result)) {
            $options .= "<option value=\"{$row['name']}\">id: {$row['id']}</option>";
        }
        return $options;
    }
    function generateTableLines($result) {
        $lines = '';
        while($rows = mysqli_fetch_row($result)) {
            $lines .= '<tr>';
                for($i = 1; $i < count($rows); $i++) {
                    $lines .= "<td>{$rows[$i]}</td>";
                }
                $lines .= "<td><a href=\"?del={$rows[0]}\">Delete</a></td>";
            $lines .= '</tr>';
        }
        return $lines;
    }

    // CONTROLLER
    function c_returnSelectStruct($type) {
        echo m_returnSelectStruct($type);
    }
    function c_returnTableStruct($type) {
        echo m_returnTableStruct($type);
    }
    function c_returnPerksList() {
        echo m_returnPerksList();
    }

    // CHECKS
    if(isset($_POST['s1'])) {
        m_insertBuild();
    }
    if(isset($_GET['del'])) {
        m_deleteBuild();
    }
?>