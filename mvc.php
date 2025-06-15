<?php
    $connection = mysqli_connect('localhost', 'root', '', 'dbd-db');
    // ------------------------------------------------

    // MODEL
    function m_getKillers() {
        global $connection;

        $contents = [];
        $kw = "SELECT id, name, image_url FROM killers";
        $wynik = mysqli_query($connection, $kw);
        if (!$wynik) {
            return [];
        }
        while($killer = mysqli_fetch_assoc($wynik)) {
            $killerId = $killer['id'];
            $killerName = $killer['name'];
            $killerImage = $killer['image_url'];
            $contents[$killerName] = [
                'type' => 'killer',
                'image' => $killerImage,
                'builds' => []
            ];
            $kw1 = "
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
                WHERE builds.character_id = $killerId AND builds.character_type = 'killer';
            ";
            $wynik1 = mysqli_query($connection, $kw1);
            if ($wynik1) {
                while($build = mysqli_fetch_assoc($wynik1)) {
                    $contents[$killerName]['builds'][$build['build_name']] = [
                        'perks' => [
                            $build['slot1_name'],
                            $build['slot2_name'],
                            $build['slot3_name'],
                            $build['slot4_name']
                        ],
                        'build-desc' => $build['build_desc']
                    ];
                }
            }
        }
        
        return createProfile($contents);
    }
    function m_getSurvs() {
        global $connection;

        $contents = [];
        $kw = "SELECT id, name, image_url FROM survivors";
        $wynik = mysqli_query($connection, $kw);
        if (!$wynik) {
            return [];
        }
        while($survivor = mysqli_fetch_assoc($wynik)) {
            $survivorId = $survivor['id'];
            $survivorName = $survivor['name'];
            $survivorImage = $survivor['image_url'];
            $contents[$survivorName] = [
                'type' => 'survivor',
                'image' => $survivorImage,
                'builds' => []
            ];
            $kw1 = "
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
                WHERE builds.character_id = $survivorId AND builds.character_type = 'survivor';
            ";
            $wynik1 = mysqli_query($connection, $kw1);
            if ($wynik1) {
                while($build = mysqli_fetch_assoc($wynik1)) {
                    $contents[$survivorName]['builds'][$build['build_name']] = [
                        'perks' => [
                            $build['slot1_name'],
                            $build['slot2_name'],
                            $build['slot3_name'],
                            $build['slot4_name']
                        ],
                        'build-desc' => $build['build_desc']
                    ];
                }
            }
        }
        
        return createProfile($contents);
    }

    // VIEW
    function createProfile($contents) {
        global $connection;

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
                            for($i = 0; $i < count($firstBuildPerks); $i++) {
                                $perkWords = explode(' ', $firstBuildPerks[$i]);
                                $perkName = '';
                                foreach($perkWords as $word) {
                                    $perkName .= $word;
                                }
                                $structure .= '<li><img src="content/perk_icons/'.$perkName.'.png" alt="'.$perkName.' icon"></li>';
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

                    $structure .= '<div class="builds-list">';
                        $structure .= '<button type="button"><i class="ri-arrow-down-line"></i></button>';
                        foreach($details['builds'] as $build => $buildData){
                        $structure .= '<div class="build">';
                            $structure .= '<h3 class="build-name">'.$build.'</h3>';
                            $structure .= '<ul class="perks-list">';
                            foreach($buildData['perks'] as $perk) {
                                $perkWords = explode(' ', $perk);
                                $perkName = '';
                                foreach($perkWords as $word) {
                                    $perkName .= $word;
                                }
                                $structure .= '<li><img src="content/perk_icons/'.$perkName.'.png" alt="'.$perkName.' icon"></li>';
                            }
                            $structure .= '</ul>';
                        $structure .= '</div>';
                        }
                    $structure .= '</div>';
                $structure .= '</div>';
            $structure .= '</div>';
        }

        return $structure;
    }
    // CONTROLLER
    function c_getKillers() {
        echo m_getKillers();
    }
    function c_getSurvs() {
        echo m_getSurvs();
    }

    // ------------------------------------------------
    // mysqli_close($connection);
?>