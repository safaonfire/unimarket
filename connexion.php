<?php
class Connexion
{
    private $connexion;
    function __construct()
    {
        try {
            $dsn = "mysql:host=localhost;dbname=ppi";
            $username = 'root';
            $password = '';
            $this->connexion = new PDO($dsn, $username, $password);
        } catch (PDOException $e) {
            echo "Problème au niveau de la connexion!!! <br> erreur: " . $e->getMessage();
        }
    }

    /**
     * Get the value of connexion
     */
    public function getConnexion()
    {
        return $this->connexion;
    }
}

