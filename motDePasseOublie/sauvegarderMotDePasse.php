<?php
require_once '../codes/codesConnexion.php';
require_once '../codes/codesChiffrage.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $emailCrypte = $_POST['email'];
    $token = $_POST['token'];
    $nouveauMotDePasse = $_POST['nouveauMotDePasse'];
    $confirmMotDePasse = $_POST['confirmMotDePasse'];

    if ($nouveauMotDePasse === $confirmMotDePasse) {
        $connexion = BaseDeDonnees::connecterBDD();
        $motDePasseCrypte = hash('sha512', $nouveauMotDePasse);

        $sql = "UPDATE utilisateur SET MotDePasse = :password, reset_token = NULL, reset_expiration = NULL WHERE Email = :email";
        $stmt = $connexion->prepare($sql);
        $stmt->bindValue(':password', $motDePasseCrypte);
        $stmt->bindValue(':email', $emailCrypte);

        if ($stmt->execute()) {
            header("Location: ../index.html");
            exit();
        } else {
            echo "Erreur lors de la mise à jour du mot de passe.";
        }
    } else {
        echo "Les mots de passe ne correspondent pas.";
    }
}
?>