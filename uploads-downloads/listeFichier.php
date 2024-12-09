<?php
session_start();

$base_dir = '../uploads/';

if (!is_dir($base_dir)) {
    die("Erreur : le répertoire des fichiers n'existe pas.");
}

function listFilesForAllUsers($dir){
    $all_files = [];
    $user_dirs = array_diff(scandir($dir), array('.', '..'));

    foreach ($user_dirs as $user_dir) {
        $user_path = $dir . DIRECTORY_SEPARATOR . $user_dir;

        if (is_dir($user_path)) {
            $files = array_diff(scandir($user_path), array('.', '..'));

            foreach ($files as $file) {
                $file_path = $user_path . DIRECTORY_SEPARATOR . $file;

                if (is_file($file_path)) {
                    $all_files[] = [
                        'user' => $user_dir,
                        'file' => $file,
                        'path' => $file_path,
                        'sha512' => hash_file('sha512', $file_path),
                    ];
                }
            }
        }
    }

    return $all_files;
}

$all_files = listFilesForAllUsers($base_dir);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <title>Liste des fichiers</title>
</head>
<body>
<div id="wrapper">
    <div class="logout-container">
        <a href="../deconnexion.php" class="logout">Se déconnecter</a>
    </div>
    <h2>Liste des fichiers téléversés par tous les utilisateurs</h2>
    <div id="fond-blanc">
        <table>
            <tr>
                <th>Utilisateur (haché)</th>
                <th>Fichier</th>
                <th>Signature SHA-512</th>
                <th>Télécharger</th>
            </tr>

            <?php foreach ($all_files as $file_info): ?>
                <tr>
                    <td><?= htmlspecialchars($file_info['user']) ?></td>
                    <td><?= htmlspecialchars($file_info['file']) ?></td>
                    <td><?= $file_info['sha512'] ?></td>
                    <td><a href="telechargerFichier.php?file=<?= urlencode($file_info['user'] . '/' . $file_info['file']) ?>">Télécharger</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        <p>Téléverser des fichiers ? <a href="deposerFichier.php">Cliquez ici</a></p>
    </div>
</div>
</body>
</html>
