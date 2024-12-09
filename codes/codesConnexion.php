<?php
class BaseDeDonnees {
    // Informations de connexion à la base de données
    private const HOST = "localhost";
    private const UTILISATEUR = "root";
    private const MOTDEPASSE = "root";
    private const BASEDEDONNES = "hash-function";

    public static function connecterBDD() {
        try {
            return new PDO('mysql:host=' . self::HOST . ';charset=utf8;dbname=' . self::BASEDEDONNES, self::UTILISATEUR, self::MOTDEPASSE,
                array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        } catch (Exception $e) {
            echo 'Erreur : ' . $e->getMessage() . '<br />';
            echo 'N° : ' . $e->getCode();
            die();
        }
    }
}
?>