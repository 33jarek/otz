<?php $conn = mysqli_connect('localhost', 'root', '', 'dbd-db');

    // MODEL
    function m_returnSelectStruct($type, $datalist) {
        global $conn;

        $allowed = ['killer', 'survivor'];
        if (!in_array($type, $allowed)) return '';

        $sql = "SELECT id, name FROM {$type}s";
        $result = mysqli_query($conn, $sql);
        return generateOptions($result, $datalist);
    }
    function m_returnPerkDetails($id) {
        global $conn;

        $sql = "SELECT id, name, obtainment, description FROM perks WHERE id = $id";
        $result = mysqli_query($conn, $sql);

        return json_encode(mysqli_fetch_assoc($result));
    }
    function m_returnPerksList($datalist) {
        global $conn;

        $sql = "SELECT id, name, obtainment, description FROM perks";
        $result = mysqli_query($conn, $sql);

        $firstRow = mysqli_fetch_assoc(mysqli_query($conn, "SELECT name, obtainment, description FROM perks LIMIT 1"));
        $optionsHtml = generateOptions($result, $datalist);

        return [
            'options' => $optionsHtml,
            'defaultPerk' => $firstRow
        ];
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
    function m_updatePerkDetails() {
        global $conn;

        $name = mysqli_real_escape_string($conn, $_POST['perk-name']);
        $obtainment = mysqli_real_escape_string($conn, $_POST['perk-obtainment']);
        $description = mysqli_real_escape_string($conn, $_POST['perk-desc']);
        $id = $_POST['perk-details'];
        
        $sql = "UPDATE perks SET name = '{$name}', obtainment = '{$obtainment}', description = '{$description}' WHERE id = $id";
        $result = mysqli_query($conn, $sql);
        
        header('Location: ?');
        exit;
    }

    // VIEW
    function generateOptions($result, $datalist) {
        $options = '';
        while($row = mysqli_fetch_assoc($result)) {
            if($datalist === true) {
                $options .= "<option value=\"{$row['name']}\">id: {$row['id']}</option>";
            } else {
                $options .= "<option value=\"{$row['id']}\">{$row['name']}</option>";
            }
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
    function c_returnSelectStruct($type, $database = false) {
        echo m_returnSelectStruct($type, $database);
    }
    function c_returnTableStruct($type) {
        echo m_returnTableStruct($type);
    }
    function c_returnPerksList($datalist = false) {
        $data = m_returnPerksList($datalist);
        echo $data['options'];
        return $data['defaultPerk'];
    }
    function c_returnPerkDetails($id) {
        echo m_returnPerkDetails($id);
    }

    // CHECKS
    if(isset($_POST['s1'])) {
        m_insertBuild();
    }
    if(isset($_GET['del'])) {
        m_deleteBuild();
    }
    if(isset($_POST['s2'])) {
        m_updatePerkDetails();
    }
?>