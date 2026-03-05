<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Programmes TV TNT de ce soir (<?php echo date("d/m/Y"); ?>)</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .channels-container { display: flex; flex-wrap: wrap; gap: 20px; }
        .channel { flex: 1 1 calc(33.333% - 20px); min-width: 300px; margin-bottom: 20px; box-sizing: border-box; }
        .programme { margin-left: 20px; }
        .no-program { color: #666; font-style: italic; }
    </style>
</head>
<body>
    <h1>Programmes TV en ce moment (<?php echo date("d/m/Y"); ?>)</h1>
(<a href=tv-night.php>ce soir</a>)<br />
    <?php
    // Liste des IDs des chaînes à afficher (dans l'ordre souhaité)
    $chainesSouhaitees = [
        'TF1.fr', 'France2.fr', 'France3.fr', 'France4.fr', 'France5.fr',
        'M6.fr', 'Arte.fr', 'LaChaineParlementaire.fr', 'PublicSenat.fr',
        'W9.fr', 'TMC.fr', 'Gulli.fr', 'TF1SeriesFilms.fr', 'T18.fr',
        'NOVO19.fr', '6ter.fr', 'LEquipe21.fr', 'NT1.fr', 'Numero23.fr',
        'RMCDecouverte.fr', 'Cherie25.fr', 'ParisPremiere.fr', 'RTL9.fr',
        'CanalPlus.fr', 'Syfy.fr', 'Teva.fr', 'Histoire.fr', 'TouteHistoire.fr',
        'PlanetePlus.fr', 'LuxeTV.fr', 'MensUpTV.fr', 'Museum.fr', 'OlympiaTV.fr', 'UshuaiaTV.fr'
    ];

    // Charger le fichier XMLTV
    $xmlFile = '../../xmltv/xmltv_fr.xml';
    $xml = simplexml_load_file($xmlFile);

    if ($xml === false) {
        echo "Erreur : Impossible de charger le fichier XML.";
        exit;
    }

    // Définir la plage horaire pour la soirée
    $startTime = strtotime(date('H').':00');
    $endTime = strtotime(date('H').':59');

    // Tableau pour stocker les programmes par chaîne
    $programmesParChaine = [];
    // Tableau pour stocker les logos des chaînes
    $logosChaines = [];

    // Parcourir les chaînes pour récupérer les logos
    foreach ($xml->channel as $channel) {
        $channelId = (string) $channel['id'];
        $iconUrl = isset($channel->icon) ? (string) $channel->icon['src'] : '';
        $logosChaines[$channelId] = $iconUrl;
    }

    // Parcourir les programmes
    foreach ($xml->programme as $programme) {
        $channelId = (string) $programme['channel'];
        $start = strtotime((string) $programme['start']);
        $stop = strtotime((string) $programme['stop']);

        // Vérifier si la chaîne est dans la liste souhaitée
        if (in_array($channelId, $chainesSouhaitees)) {
            // Afficher le programme s'il est en cours à $startTime ou s'il commence après
            if ($stop > $startTime && $start < $endTime) {
                $title = (string) $programme->title;
                $desc = isset($programme->desc) ? (string) $programme->desc : 'Aucune description disponible';
				$durationMinutes = ($stop - $start) / 60; // Durée en minutes
				
                $programmesParChaine[$channelId][] = [
                    'start' => date('H:i', $start),
                    'stop'  => date('H:i', $stop),
                    'title' => $title,
                    'desc'  => $desc,
					'duration' => $durationMinutes
                ];
            }
        }
    }
    ?>

    <div class="channels-container">
        <?php
        // Afficher les chaînes dans l'ordre du tableau $chainesSouhaitees
        foreach ($chainesSouhaitees as $channelId) {
            echo "<div class='channel'>";
            echo "<h2>";
            if (isset($logosChaines[$channelId]) && !empty($logosChaines[$channelId])) {
                echo "<img src='" . $logosChaines[$channelId] . "' alt='Logo $channelId' style='width:100px;height:auto;'>";
            } else {
                echo $channelId; // Afficher le nom de la chaîne si pas de logo
            }
            echo "</h2>";

            // Vérifier si des programmes existent pour cette chaîne
            if (isset($programmesParChaine[$channelId]) && !empty($programmesParChaine[$channelId])) {
                foreach ($programmesParChaine[$channelId] as $programme) {
                    echo "<div class='programme'><br />";
                    echo "<strong>{$programme['start']} - {$programme['stop']} : {$programme['title']}</strong><br />";
                    if ($programme['duration'] > 20) {
                        echo $programme['desc'];
                    }
                    echo "</div>";
                }
            } else {
                echo "<div class='no-program'>Aucun programme trouvé pour cette plage horaire.</div>";
            }
            echo "</div>";
        }
        ?>
    </div>
</body>
</html>
