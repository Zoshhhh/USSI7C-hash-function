<?php
session_start();

require_once '../codes/codesConnexion.php';
$connexion = BaseDeDonnees::connecterBDD();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $motDePasse = $_POST['motDePasse'];

    require_once '../codes/codesChiffrage.php';
    $emailCrypte = openssl_encrypt($email, CIPHERAGE, CLE_CHIFFREMENT, OPTIONS, IV_CHIFFREMENT);
    $motDePasseCrypte = hash('sha512', $motDePasse);

    $sql = "SELECT Email, MotDePasse FROM utilisateur WHERE Email = :email AND MotDePasse = :motDePasse";
    $stmt = $connexion->prepare($sql);
    $stmt->bindValue(':email', $emailCrypte);
    $stmt->bindValue(':motDePasse', $motDePasseCrypte);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $_SESSION['emailCrypte'] = $emailCrypte;
        header("Location: ../uploads-downloads/deposerFichier.php");
        exit();
    } else {
        echo "Erreur lors de la création du compte.";
    }
}
?>
