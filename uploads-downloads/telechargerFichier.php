<?php
session_start();

if (!isset($_SESSION['emailCrypte'])) {
    die("Erreur : vous devez être authentifié pour télécharger un fichier.");
}

$user_dir = '../uploads/' . hash('sha256', $_SESSION['emailCrypte']) . '/';

if (isset($_GET['file'])) {
    $file = basename($_GET['file']);
    $file_path = $user_dir . $file;

    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . basename($file_path) . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file_path));

    readfile($file_path);
} else {
    echo "Erreur : aucun fichier spécifié.";
}
?>
