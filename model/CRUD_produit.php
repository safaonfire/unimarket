<?php
require_once __DIR__ . '/../connexion.php';

class CRUD_produit {
    private $pdo;

    function __construct() {
        $con = new Connexion();
        $this->pdo = $con->getConnexion();
    }

    public function getAll($tri = 'defaut') {
        $order = match($tri) {
            'popularite' => 'ORDER BY p.nb_likes DESC',
            'recent'     => 'ORDER BY p.id DESC',
            'ancien'     => 'ORDER BY p.id ASC',
            'prix_asc'   => 'ORDER BY COALESCE(NULLIF(p.prix_promo,0), p.prix) ASC',
            'prix_desc'  => 'ORDER BY COALESCE(NULLIF(p.prix_promo,0), p.prix) DESC',
            default      => ''
        };
        $sql = "SELECT p.*, u.nom AS vendeur_nom, u.prenom AS vendeur_prenom, c.nom AS categorie
                FROM produit p
                LEFT JOIN user u ON p.id_proprietaire = u.id
                LEFT JOIN categorie c ON p.id_categorie = c.id
                $order";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByCategorie($id_categorie, $tri = 'defaut') {
        $order = match($tri) {
            'popularite' => 'ORDER BY p.nb_likes DESC',
            'recent'     => 'ORDER BY p.id DESC',
            'ancien'     => 'ORDER BY p.id ASC',
            'prix_asc'   => 'ORDER BY COALESCE(NULLIF(p.prix_promo,0), p.prix) ASC',
            'prix_desc'  => 'ORDER BY COALESCE(NULLIF(p.prix_promo,0), p.prix) DESC',
            default      => ''
        };
        $sql = "SELECT p.*, u.nom AS vendeur_nom, u.prenom AS vendeur_prenom, c.nom AS categorie
                FROM produit p
                LEFT JOIN user u ON p.id_proprietaire = u.id
                LEFT JOIN categorie c ON p.id_categorie = c.id
                WHERE p.id_categorie = ?
                $order";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_categorie]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByProprietaire($id) {
        $sql = "SELECT p.*, c.nom AS categorie FROM produit p
                LEFT JOIN categorie c ON p.id_categorie = c.id
                WHERE p.id_proprietaire = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM produit WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function update($id, $libelle, $prix, $quantite, $description, $promo, $id_categorie, $image = null, $prix_promo = null, $handmade = 0) {
        if ($image) {
            $sql = "UPDATE produit SET libelle=?, prix=?, quantite=?, description=?, promo=?, id_categorie=?, image=?, prix_promo=?, handmade=? WHERE id=?";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$libelle, $prix, $quantite, $description, $promo, $id_categorie ?: null, $image, $prix_promo, $handmade, $id]);
        } else {
            $sql = "UPDATE produit SET libelle=?, prix=?, quantite=?, description=?, promo=?, id_categorie=?, prix_promo=?, handmade=? WHERE id=?";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$libelle, $prix, $quantite, $description, $promo, $id_categorie ?: null, $prix_promo, $handmade, $id]);
        }
    }

    public function getById($id) {
        $sql = "SELECT p.*, u.nom AS vendeur_nom, u.prenom AS vendeur_prenom, c.nom AS categorie
                FROM produit p
                LEFT JOIN user u ON p.id_proprietaire = u.id
                LEFT JOIN categorie c ON p.id_categorie = c.id
                WHERE p.id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function add($libelle, $prix, $quantite, $image, $description, $promo, $id_proprietaire, $id_categorie, $prix_promo = null, $handmade = 0) {
        $sql = "INSERT INTO produit (libelle, prix, quantite, image, description, promo, id_proprietaire, id_categorie, prix_promo, handmade)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$libelle, $prix, $quantite, $image, $description, $promo, $id_proprietaire, $id_categorie ?: null, $prix_promo, $handmade]);
    }

    public function search($mot)
    {
        $stmt = $this->pdo->prepare("
            SELECT p.*, c.nom AS categorie, u.nom AS vendeur_nom, u.prenom AS vendeur_prenom
            FROM produit p
            LEFT JOIN categorie c ON p.id_categorie = c.id
            LEFT JOIN user u ON p.id_proprietaire = u.id
            WHERE p.libelle LIKE :mot
               OR p.description LIKE :mot
               OR u.nom LIKE :mot
               OR u.prenom LIKE :mot
               OR CONCAT(u.prenom, ' ', u.nom) LIKE :mot
               OR CONCAT(u.nom, ' ', u.prenom) LIKE :mot
            ORDER BY p.id DESC
        ");
        $stmt->execute(['mot' => '%' . $mot . '%']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCategories() {
        $stmt = $this->pdo->prepare("SELECT * FROM categorie ORDER BY COALESCE(parent_id, id), parent_id IS NOT NULL, id");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCategoriesFlat() {
        $stmt = $this->pdo->prepare("SELECT * FROM categorie ORDER BY COALESCE(parent_id, id), parent_id IS NOT NULL, id");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCategoriesTree() {
        $stmt = $this->pdo->prepare("SELECT * FROM categorie ORDER BY COALESCE(parent_id, id), parent_id IS NOT NULL, id");
        $stmt->execute();
        $all = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $tree = [];
        $map = [];

        foreach ($all as $cat) {
            $cat['children'] = [];
            $map[$cat['id']] = $cat;
        }

        foreach ($map as $id => $cat) {
            if (is_null($cat['parent_id'])) {
                $tree[] = &$map[$id];
            } elseif (isset($map[$cat['parent_id']])) {
                $map[$cat['parent_id']]['children'][] = &$map[$id];
            }
        }

        return $tree;
    }

    public function getVendeurs() {
        $stmt = $this->pdo->prepare("SELECT id, nom, prenom FROM user WHERE role = 'admin'");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>

