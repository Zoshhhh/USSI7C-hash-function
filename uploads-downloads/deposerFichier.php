<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['file'];

        if (isset($_SESSION['emailCrypte'])) {
            $user_email = $_SESSION['emailCrypte'];
        } else {
            die("Erreur : l'utilisateur n'est pas authentifié.");
        }

        $upload_dir = '../uploads/' . hash('sha256', $user_email) . '/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $destination = $upload_dir . basename($file['name']);
        if (move_uploaded_file($file['tmp_name'], $destination)) {
            $message = "Fichier téléversé avec succès.";
            $message_class = "success";
        } else {
            $message = "Erreur lors du téléversement du fichier.";
            $message_class = "error";
        }
    } else {
        $message = "Aucun fichier n'a été sélectionné.";
        $message_class = "error";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <title>Téléversement de fichiers</title>
</head>
<body>
<div id="wrapper">
    <div class="logout-container">
        <a href="../deconnexion.php" class="logout">Se déconnecter</a>
    </div>

    <h2>Téléversement de fichiers</h2>

    <form method="post" enctype="multipart/form-data">
        <label for="file">Fichier :</label>
        <input type="file" id="file" name="file" required>
        <br>

        <?php if (isset($message)) { ?>
            <div class="message <?= $message_class ?>">
                <?= $message ?>
            </div>
        <?php } ?>

        <button type="submit">Téléverser</button>

        <p>Liste des fichiers déposés. <a href="listeFichier.php">Cliquez ici</a></p>
    </form>
</div>
</body>
</html>