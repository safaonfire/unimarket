-- Table panier persistant
CREATE TABLE IF NOT EXISTS panier (
    id_user    INT NOT NULL,
    id_produit INT NOT NULL,
    quantite   INT NOT NULL DEFAULT 1,
    PRIMARY KEY (id_user, id_produit),
    FOREIGN KEY (id_user)    REFERENCES user(id) ON DELETE CASCADE,
    FOREIGN KEY (id_produit) REFERENCES produit(id) ON DELETE CASCADE
);
