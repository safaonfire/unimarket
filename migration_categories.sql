-- =============================================
-- RECRÉATION COMPLÈTE : categorie, produit, commande
-- =============================================

SET FOREIGN_KEY_CHECKS = 0;

-- =====================
-- TABLE : categorie
-- =====================
CREATE TABLE categorie (
    id        INT AUTO_INCREMENT PRIMARY KEY,
    nom       VARCHAR(100) NOT NULL,
    parent_id INT DEFAULT NULL,
    FOREIGN KEY (parent_id) REFERENCES categorie(id) ON DELETE CASCADE
);

-- =====================
-- TABLE : produit
-- =====================
CREATE TABLE produit (
    id             INT AUTO_INCREMENT PRIMARY KEY,
    libelle        VARCHAR(255) NOT NULL,
    prix           DECIMAL(10,2) NOT NULL,
    quantite       INT NOT NULL DEFAULT 0,
    image          VARCHAR(255),
    description    TEXT,
    promo          TINYINT(1) DEFAULT 0,
    prix_promo     DECIMAL(10,2) DEFAULT NULL,
    id_proprietaire INT NOT NULL,
    id_categorie   INT DEFAULT NULL,
    FOREIGN KEY (id_proprietaire) REFERENCES user(id) ON DELETE CASCADE,
    FOREIGN KEY (id_categorie)    REFERENCES categorie(id) ON DELETE SET NULL
);

-- =====================
-- TABLE : commande
-- =====================
CREATE TABLE commande (
    id                INT AUTO_INCREMENT PRIMARY KEY,
    id_client         INT NOT NULL,
    id_produit        INT NOT NULL,
    quantite          INT NOT NULL DEFAULT 1,
    description       TEXT,
    statut            ENUM('en_attente','confirmee','annulee') DEFAULT 'en_attente',
    date_commande     DATETIME DEFAULT NOW(),
    date_confirmation DATETIME DEFAULT NULL,
    FOREIGN KEY (id_client)  REFERENCES user(id) ON DELETE CASCADE,
    FOREIGN KEY (id_produit) REFERENCES produit(id) ON DELETE CASCADE
);

-- =====================
-- TABLE : favoris
-- =====================
CREATE TABLE IF NOT EXISTS favoris (
    id_user    INT NOT NULL,
    id_produit INT NOT NULL,
    PRIMARY KEY (id_user, id_produit),
    FOREIGN KEY (id_user)    REFERENCES user(id) ON DELETE CASCADE,
    FOREIGN KEY (id_produit) REFERENCES produit(id) ON DELETE CASCADE
);

SET FOREIGN_KEY_CHECKS = 1;

-- =============================================
-- INSERTION DES CATÉGORIES
-- =============================================

-- 1. Accessoires
INSERT INTO categorie (id, nom, parent_id) VALUES (1, 'Accessoires', NULL);
INSERT INTO categorie (id, nom, parent_id) VALUES (2, 'Bijoux', 1);
INSERT INTO categorie (id, nom, parent_id) VALUES (3, 'Colliers', 2);
INSERT INTO categorie (id, nom, parent_id) VALUES (4, 'Bracelets', 2);
INSERT INTO categorie (id, nom, parent_id) VALUES (5, 'Bagues', 2);
INSERT INTO categorie (id, nom, parent_id) VALUES (6, "Boucles d'oreilles", 2);
INSERT INTO categorie (id, nom, parent_id) VALUES (7, 'Accessoires Téléphone', 1);
INSERT INTO categorie (id, nom, parent_id) VALUES (8, 'Coques', 7);
INSERT INTO categorie (id, nom, parent_id) VALUES (9, 'Popsockets', 7);
INSERT INTO categorie (id, nom, parent_id) VALUES (10, 'Supports téléphone', 7);
INSERT INTO categorie (id, nom, parent_id) VALUES (11, 'Chargeurs / câbles', 7);
INSERT INTO categorie (id, nom, parent_id) VALUES (12, 'Accessoires Maison', 1);
INSERT INTO categorie (id, nom, parent_id) VALUES (13, 'Décoration', 12);
INSERT INTO categorie (id, nom, parent_id) VALUES (14, 'Bougies', 12);
INSERT INTO categorie (id, nom, parent_id) VALUES (15, 'Cadres', 12);
INSERT INTO categorie (id, nom, parent_id) VALUES (16, 'Objets artisanaux', 12);

-- 2. Produits Personnalisés
INSERT INTO categorie (id, nom, parent_id) VALUES (17, 'Produits Personnalisés', NULL);
INSERT INTO categorie (id, nom, parent_id) VALUES (18, 'Stickers personnalisés', 17);
INSERT INTO categorie (id, nom, parent_id) VALUES (19, 'Photos imprimées', 17);
INSERT INTO categorie (id, nom, parent_id) VALUES (20, 'Posters', 17);
INSERT INTO categorie (id, nom, parent_id) VALUES (21, 'T-shirts & Hoodies', 17);
INSERT INTO categorie (id, nom, parent_id) VALUES (22, 'Tote bag', 17);

-- 3. Cadeaux & Lifestyle
INSERT INTO categorie (id, nom, parent_id) VALUES (23, 'Cadeaux & Lifestyle', NULL);
INSERT INTO categorie (id, nom, parent_id) VALUES (24, 'Idées cadeaux', 23);
INSERT INTO categorie (id, nom, parent_id) VALUES (25, 'Produits lifestyle', 23);
