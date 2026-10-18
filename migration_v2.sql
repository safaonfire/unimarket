-- Ajouter prix_unitaire et adresse_livraison dans commande
ALTER TABLE commande ADD COLUMN prix_unitaire DECIMAL(10,2) DEFAULT NULL AFTER quantite;
ALTER TABLE commande ADD COLUMN adresse_livraison VARCHAR(255) DEFAULT NULL AFTER prix_unitaire;
ALTER TABLE commande ADD COLUMN telephone VARCHAR(20) DEFAULT NULL AFTER adresse_livraison;
