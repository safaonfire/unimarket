<?php

require_once __DIR__ . '/../connexion.php';

class CRUD_commande
{
    private $pdo;

    public function __construct()
    {
        $conn = new Connexion();
        $this->pdo = $conn->getConnexion();
    }

    public function getCommandesByClient($client_id)
    {
        $stmt = $this->pdo->prepare("
            SELECT c.*, p.libelle, p.prix, p.image
            FROM commande c
            JOIN produit p ON c.id_produit = p.id
            WHERE c.id_client = :client_id
            ORDER BY c.id DESC
        ");
        $stmt->execute(['client_id' => $client_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getCommandesByVendeur($vendeur_id)
    {
        $stmt = $this->pdo->prepare("
            SELECT c.*, p.libelle, p.prix, p.image, u.nom AS client_nom, u.prenom AS client_prenom, u.tel AS client_tel
            FROM commande c
            JOIN produit p ON c.id_produit = p.id
            JOIN user u ON c.id_client = u.id
            WHERE p.id_proprietaire = :vendeur_id
            ORDER BY c.id DESC
        ");
        $stmt->execute(['vendeur_id' => $vendeur_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateStatut($id, $statut)
    {
        if ($statut === 'confirmee') {
            $stmt = $this->pdo->prepare("UPDATE commande SET statut = :statut, date_confirmation = NOW() WHERE id = :id");
        } else {
            $stmt = $this->pdo->prepare("UPDATE commande SET statut = :statut, date_confirmation = NULL WHERE id = :id");
        }
        return $stmt->execute(['statut' => $statut, 'id' => $id]);
    }

    public function ajouterCommande($data)
    {
        // Insérer la commande avec le prix au moment de la commande
        $stmt = $this->pdo->prepare("
            INSERT INTO commande (id_client, id_produit, quantite, prix_unitaire, adresse_livraison, telephone, description)
            VALUES (:client_id, :produit_id, :quantite, :prix_unitaire, :adresse_livraison, :telephone, :description)
        ");
        $result = $stmt->execute([
            'client_id'        => $data['client_id'],
            'produit_id'       => $data['produit_id'],
            'quantite'         => $data['quantite'],
            'prix_unitaire'    => $data['prix_unitaire'],
            'adresse_livraison'=> $data['adresse_livraison'] ?? null,
            'telephone'        => $data['telephone'] ?? null,
            'description'      => $data['description'] ?? null,
        ]);

        // Décrémenter le stock
        if ($result) {
            $this->pdo->prepare("UPDATE produit SET quantite = GREATEST(0, quantite - ?) WHERE id = ?")
                ->execute([$data['quantite'], $data['produit_id']]);
        }

        return $result;
    }
}

