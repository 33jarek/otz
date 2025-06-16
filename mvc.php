<?php $conn = mysqli_connect('localhost', 'root', '', 'dbd-db');

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

    // VIEW
    function renderPerkImageListItem($perkName) {
        $convertedName = '';
        $perkWords = explode(' ', $perkName);
        foreach($perkWords as $word) {
            if(strpos($word, "'") !== false) {
                $word = str_replace("'", "", $word);
            }
            if(strpos($word, ":") !== false) {
                $word = str_replace(":", "", $word);
            }
            $convertedName .= $word;
        }
        $perkListItems = '<li><img src="content/perk_icons/'.$convertedName.'.png" alt="'.$perkName.' icon"></li>';
        return $perkListItems;
    }
    function createCharacterPanels($contents) {
        $structure = '';
        foreach($contents as $killer => $details) {
            $structure .= '<div class="character-profile">';

                $structure .= '<div class="character-image">';
                    $structure .= '<div class="character-background"></div>';
                    $structure .= '<div class="role-fog is-'.$details['type'].'"></div>';
                    $structure .= '<img src="content/'.$details['type'].'_portraits/'.$details['image'].'" alt="'.$killer.' image">';
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

?>