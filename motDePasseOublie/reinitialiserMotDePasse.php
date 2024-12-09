<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

require_once '../codes/codesConnexion.php';
require_once '../codes/codesChiffrage.php';

$emailCrypte = $_GET['email'];
$token = $_GET['token'];

$connexion = BaseDeDonnees::connecterBDD();
$sql = "SELECT * FROM utilisateur WHERE Email = :email AND reset_token = :token AND reset_expiration > NOW()";
$stmt = $connexion->prepare($sql);
$stmt->bindValue(':email', $emailCrypte);
$stmt->bindValue(':token', $token);
$stmt->execute();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <title>Nouveau mot de passe</title>
</head>
<body>
<div id="wrapper">
    <h2>Nouveau mot de passe</h2>
    <form action="sauvegarderMotDePasse.php" method="POST">
        <input type="hidden" name="email" value="<?php echo htmlspecialchars($emailCrypte); ?>">
        <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">

        <label for="nouveauMotDePasse">Nouveau mot de passe :</label>
        <input type="password" id="nouveauMotDePasse" name="nouveauMotDePasse" required>

        <label for="confirmMotDePasse">Confirmer le mot de passe :</label>
        <input type="password" id="confirmMotDePasse" name="confirmMotDePasse" required>

        <button type="submit">Changer le mot de passe</button>
    </form>
</div>
</body>
</html>