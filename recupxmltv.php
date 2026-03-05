<?php
// URL du fichier ZIP à télécharger
// TNT only $zipUrl = 'https://xmltvfr.fr/xmltv/xmltv_tnt.zip';
$zipUrl = 'https://xmltvfr.fr/xmltv/xmltv_fr.zip';

// Chemin local où le fichier ZIP sera enregistré
// $zipFile = 'xmltv_tnt.zip';
$zipFile = 'xmltv_fr.zip';

// Chemin du dossier où le contenu sera extrait
$extractTo = 'xmltv/';

// Fonction pour envoyer un email en cas d'erreur
function sendErrorEmail($errorMessage) {
    $to = 'XXX@XXX.COM'; // Remplacez par votre adresse email
    $subject = 'Erreur lors du traitement du fichier ZIP';
    $message = 'Une erreur est survenue : ' . $errorMessage;
    $headers = 'From: no-reply@XXX.COM' . "\r\n" .
               'Reply-To: no-reply@XXX.COM' . "\r\n" .
               'X-Mailer: PHP/' . phpversion();

    // Envoyer l'email
    if (!mail($to, $subject, $message, $headers)) {
        echo "Échec de l'envoi de l'email d'erreur.";
    }
}

// Télécharger le fichier ZIP
$zipContent = file_get_contents($zipUrl);
if ($zipContent === FALSE) {
    sendErrorEmail('Erreur lors du téléchargement du fichier ZIP.');
    die('Erreur lors du téléchargement du fichier ZIP.');
}

// Enregistrer le fichier ZIP localement
if (file_put_contents($zipFile, $zipContent) === FALSE) {
    sendErrorEmail('Erreur lors de l\'enregistrement du fichier ZIP.');
    die('Erreur lors de l\'enregistrement du fichier ZIP.');
}

// Créer le dossier d'extraction s'il n'existe pas
if (!is_dir($extractTo)) {
    if (!mkdir($extractTo, 0777, true)) {
        sendErrorEmail('Erreur lors de la création du dossier d\'extraction.');
        die('Erreur lors de la création du dossier d\'extraction.');
    }
}

// Décompresser le fichier ZIP
$zip = new ZipArchive;
if ($zip->open($zipFile) === TRUE) {
    if (!$zip->extractTo($extractTo)) {
        sendErrorEmail('Erreur lors de l\'extraction du fichier ZIP.');
        die('Erreur lors de l\'extraction du fichier ZIP.');
    }
    $zip->close();
    echo "Fichier décompressé avec succès dans le dossier $extractTo.";
} else {
    sendErrorEmail('Erreur lors de l\'ouverture du fichier ZIP.');
    echo "Erreur lors de l'ouverture du fichier ZIP.";
}
?>
