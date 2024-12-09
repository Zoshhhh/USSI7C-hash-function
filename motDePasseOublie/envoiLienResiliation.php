<?php
require_once '../codes/codesConnexion.php';
require_once '../codes/codesChiffrage.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header('Location: motDePasseOublie.php?message=' . urlencode("PDF invalide"));
        exit();
    }

    $connexion = BaseDeDonnees::connecterBDD();

    $emailCrypte = openssl_encrypt($email, CIPHERAGE, CLE_CHIFFREMENT, OPTIONS, IV_CHIFFREMENT);
    $sql = "SELECT * FROM utilisateur WHERE Email = :email";
    $stmt = $connexion->prepare($sql);
    $stmt->bindValue(':email', $emailCrypte);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $token = bin2hex(random_bytes(32)); // Token sécurisé
        $expiration = date('Y-m-d H:i:s', strtotime('+1 hour'));

        $sql = "UPDATE utilisateur SET reset_token = :token, reset_expiration = :expiration WHERE Email = :email";
        $stmt = $connexion->prepare($sql);
        $stmt->bindValue(':token', $token);
        $stmt->bindValue(':expiration', $expiration);
        $stmt->bindValue(':email', $emailCrypte);
        $stmt->execute();

        // Construction de l'URL de réinitialisation
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
        $host = $_SERVER['HTTP_HOST'];
        $currentPath = dirname($_SERVER['SCRIPT_NAME']); // Chemin du fichier actuel
        $resetPath = '/reinitialiserMotDePasse.php'; // Chemin relatif au fichier de réinitialisation

        $resetLink = $protocol . $host . $currentPath . $resetPath . '?' . http_build_query([
                'email' => $emailCrypte,
                'token' => $token,
            ]);


        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'mail1.netim.hosting';
            $mail->SMTPAuth = true;
            $mail->Username = 'info@jura-france.net';
            $mail->Password = '2zxcv211G';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;
            $mail->CharSet = 'UTF-8';

            $mail->setFrom('info@jura-france.net', 'Réinitialisation de mot de passe');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Réinitialisation de votre mot de passe';

            // Contenu HTML
            $mail->Body = '
                        <html lang="fr">
                            <body style="font-family: Arial, sans-serif;">
                                <h2>Réinitialisation de votre mot de passe</h2>
                                <p>Vous avez demandé la réinitialisation de votre mot de passe.</p>
                                <p>Cliquez sur le lien ci-dessous pour procéder à la réinitialisation (ce lien est valable pendant 1 heure) :</p>
                                <p><a href="' . htmlspecialchars($resetLink) . '" style="background-color: #4CAF50; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Réinitialiser mon mot de passe</a></p>
                                <p>Si vous n\'avez pas demandé cette réinitialisation, veuillez ignorer cet email.</p>
                                <p>Ce lien expirera le ' . date('d/m/Y à H:i', strtotime($expiration)) . '.</p>
                            </body>
                        </html>';

            // Version texte alternative
            $mail->AltBody = "Réinitialisation de votre mot de passe\n\n" .
                "Vous avez demandé la réinitialisation de votre mot de passe.\n" .
                "Cliquez sur ce lien pour réinitialiser votre mot de passe (valide pendant 1 heure) :\n" .
                $resetLink;

            $mail->send();
            $message = "Un email de réinitialisation vous a été envoyé.";
        } catch (Exception $e) {
            error_log("Erreur d'envoi d'email: " . $e->getMessage());
            $message = "Une erreur est survenue lors de l'envoi de l'email. Veuillez réessayer plus tard.";
        }
    } else {
        // Message générique
        $message = "Si cette adresse email existe dans notre base, vous recevrez un lien de réinitialisation.";
    }

    header('Location: motDePasseOublie.php?message=' . urlencode($message));
    exit();
}
?>