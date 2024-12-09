<?php
require_once '../codes/codesConnexion.php';
$connexion = BaseDeDonnees::connecterBDD();

$email = $_POST['email'];
$motDePasse = $_POST['motDePasse'];

require_once '../codes/codesChiffrage.php';
$emailCrypte = openssl_encrypt($email, CIPHERAGE, CLE_CHIFFREMENT, OPTIONS, IV_CHIFFREMENT);
$motDePasseCrypte = hash('sha512', $motDePasse);

$sql = "INSERT INTO utilisateur (Email, MotDePasse) VALUES (:email, :password)";
$stmt = $connexion->prepare($sql);
$stmt->bindValue(':email', $emailCrypte);
$stmt->bindValue(':password', $motDePasseCrypte);

if ($stmt->execute()) {
    header("Location: ../index.html");
    exit();
} else {
    echo "Erreur lors de la création du compte.";
}
?>