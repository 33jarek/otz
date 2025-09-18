<?php $conn = mysqli_connect('localhost', 'root', '', 'dbd-db');

    // $noApost = mysqli_real_escape_string($conn, 'Your prayers invoke a dark power that meddles with the Survivors\' chances of survival. <ul><li>At the start of the Trial, the <span class="object">3 Generators</span> located farthest from you are blocked by The <a href="https://deadbydaylight.wiki.gg/wiki/Entity" target="_blank" class="link">Entity</a> for <span class="t1">80</span>/<span class="t2">100</span>/<span class="t3">120</span> seconds.</li></ul><p class="note">Corrupt Intervention deactivates prematurely once the first Survivor is put into the <a href="https://deadbydaylight.wiki.gg/wiki/Health_States#Dying_State" target="_blank" class="link">Dying State</a>.</p><p class="quote">"It shall be known across the land that the Gods curse the unfaithful." — (The Tablet of Adiris, 3.7)</p>');
    // $sql = "UPDATE perks SET description = '$noApost' WHERE id = 53";
    // mysqli_query($conn, $sql);

    // MODEL
    function m_displayCharacters($type) {
        global $conn;

        $sql = "SELECT id, name, image_url FROM {$type}s";
        $result = mysqli_query($conn, $sql);

        $contents = m_returnCharacters($result, $type);
        return createCharacterPanels($contents);
    }
    function m_returnCharacters($result, $type) {
        $contents = [];
        while($char = mysqli_fetch_assoc($result)) {
            $charId    = $char['id'];
            $charName  = $char['name'];
            $charImage = $char['image_url'];
            $contents[$charName] = [
                'type' => "$type",
                'image' => $charImage,
                'builds' => m_returnBuilds($type, $charId)
            ];
        }
        return $contents;
    }
    function m_returnBuilds($type, $charId) {
        global $conn;

        $contents = [];
        $sql = "
            SELECT 
                builds.name AS build_name,
                builds.description AS build_desc,
                perk1.name AS slot1_name,
                perk2.name AS slot2_name,
                perk3.name AS slot3_name,
                perk4.name AS slot4_name
            FROM builds
            JOIN perks AS perk1 ON builds.slot1 = perk1.id
            JOIN perks AS perk2 ON builds.slot2 = perk2.id
            JOIN perks AS perk3 ON builds.slot3 = perk3.id
            JOIN perks AS perk4 ON builds.slot4 = perk4.id
            WHERE builds.character_id = $charId AND builds.character_type = '$type';
        ";
        $result = mysqli_query($conn, $sql);
        while($build = mysqli_fetch_assoc($result)) {
            $contents[$build['build_name']] = [
                'perks' => [
                    $build["slot1_name"],
                    $build["slot2_name"],
                    $build["slot3_name"],
                    $build["slot4_name"]
                ],
                'build-desc' => $build["build_desc"]
            ];
        };
        return $contents;
    }
    function m_returnPerkInfo($name) {
        global $conn;

        $name = mysqli_real_escape_string($conn, $name);
        $sql = "SELECT * FROM perks WHERE name = '{$name}'";
        $result = mysqli_query($conn, $sql);

        return renderPerkInfo($result);
    }
    function formatPerkName($string, $chars) {
        $newString = '';
        $stringWords = explode(' ', $string);
        foreach ($stringWords as $word) {
            foreach ($chars as $char) {
                $word = str_replace($char, '', $word);
            }
            $newString .= $word;
        }
        return $newString;
    }

    // VIEW
    function renderPerkInfo($result) {
        $info = '';
        $row = mysqli_fetch_assoc($result);
        if(!$row) return '<p>PERK NOT FOUND!</p>';

        $info .= "<h3>This perk is obtained from {$row['obtainment']}</h3>";
        $info .= '<div class="perk-details">';
            $info .= '<div class="about-perk">';
                $info .= "<h2>{$row['name']}</h2>";
                $info .= '<div class="details-holder">';
                    $info .= "<p>{$row['description']}</p>";
                $info .= '</div>';
            $info .= '</div>';
            $imgName = formatPerkName($row['name'], ["'", ":"]);
            $info .= "<div><img src=\"assets/images/perk_icons/{$imgName}.png\" alt=\"{$row['name']} icon\"></div>";
        $info .= '</div>';

        return $info;
    }
    function renderPerkImageListItem($perkName) {
        $convertedName = formatPerkName($perkName, ["'", ":"]);
        $perkListItems = '<li><img class="perk-icon" src="assets/images/perk_icons/'.$convertedName.'.png" alt="'.$perkName.' icon"></li>';
        return $perkListItems;
    }
    function createCharacterPanels($contents) {
        $structure = '';
        foreach($contents as $killer => $details) {
            $structure .= '<div class="character-profile">';

                $structure .= '<div class="character-image">';
                    $structure .= '<div class="character-background"></div>';
                    $structure .= '<div class="role-fog is-'.$details['type'].'"></div>';
                    $structure .= '<img src="assets/images/'.$details['type'].'_portraits/'.$details['image'].'" alt="'.$killer.' image">';
                    $structure .= '<h2 class="character-name">'.$killer.'</h2>';
                $structure .= '</div>';

                $structure .= '<div class="character-content">';
                    $structure .= '<div class="build main-build">';

                    if(!empty($details['builds'])) {
                        $firstBuildName = array_key_first($details['builds']);

                        $structure .= '<h2 class="build-name">'.$firstBuildName.'</h2>';
                        $structure .= '<ul class="perks-list">';
                            // $structure .= '<div class="perks-background"></div>';
                            $firstBuildPerks = $details['builds'][$firstBuildName]['perks'];
                            foreach($firstBuildPerks as $perkName) {
                                $structure .= renderPerkImageListItem($perkName);
                            }
                        $structure .= '</ul>';

                        $structure .= '<div class="additional-info">';
                            $structure .= '<div class="about-build">';
                                $structure .= '<h3>About the build:</h3>';
                                $structure .= '<ul class="main-build-info">';
                                    $descItems = explode(', ', $details['builds'][$firstBuildName]['build-desc']);
                                    foreach($descItems as $desc) {
                                        $structure .= '<li>'.$desc.'</li>';
                                    }
                                $structure .= '</ul>';
                            $structure .= '</div>';
                            $structure .= '<div class="more-builds">';
                                $structure .= '<p class="builds-count">...and '.(count($details['builds']) - 1).' more</p>';
                                $structure .= '<button type="button" class="btn-show-list">Show full list</button>';
                            $structure .= '</div>';
                        $structure .= '</div>';
                    }
                    $structure .= '</div>';

                    $structure .= '<dialog class="builds-list">';
                        $structure .= '<form method="dialog" class="dialog-form">';
                            $structure .= '<button class="close-dialog-btn"><i class="ri-close-large-fill"></i></button>';
                            $structure .= '<div class="dialog-form-content">';
                                foreach($details['builds'] as $build => $buildData){
                                $structure .= '<div class="build">';
                                    $structure .= '<h2 class="build-name">'.$build.'</h2>';
                                    $structure .= '<ul class="perks-list">';
                                    foreach($buildData['perks'] as $perkName) {
                                        $structure .= renderPerkImageListItem($perkName);
                                    }
                                    $structure .= '</ul>';
                                    $structure .= '<div class="additional-info">';
                                        $structure .= '<div class="about-build">';
                                            $structure .= '<h3>About the build:</h3>';
                                            $structure .= '<ul class="build-info">';
                                                $descItems = explode(', ', $buildData['build-desc']);
                                                foreach($descItems as $desc) {
                                                    $structure .= '<li>'.$desc.'</li>';
                                                }
                                            $structure .= '</ul>';
                                        $structure .= '</div>';
                                    $structure .= '</div>';
                                $structure .= '</div>';
                                }
                            $structure .= '</div>';
                        $structure .= '</form>';
                    $structure .= '</dialog>';
                $structure .= '</div>';
            $structure .= '</div>';
        }

        return $structure;
    }
    
    // CONTROLLER
    function c_displayCharacters($type) {
        echo m_displayCharacters($type);
    }
    function c_returnPerkInfo($name) {
        echo m_returnPerkInfo($name);
    }

?>