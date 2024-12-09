<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mot de passe oublié</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<div id="wrapper">
    <h2>Mot de passe oublié</h2>
    <form action="envoiLienResiliation.php" method="POST">
        <label for="email">Email :</label>
        <input type="email" id="email" name="email" required>
        <?php
        if(isset($_GET['message'])): ?>
            <div class="message success">
                <?= $_GET['message'] ?>
            </div>
        <?php
        endif;
        ?>

        <button type="submit">Réinitialiser le mot de passe</button>
        <p>Se connecter ? <a href="../index.html">Cliquez ici</a>.</p>
    </form>
</div>
</body>
</html>