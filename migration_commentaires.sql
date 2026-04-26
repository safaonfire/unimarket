CREATE TABLE IF NOT EXISTS commentaire (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    id_produit INT NOT NULL,
    id_user    INT NOT NULL,
    contenu    TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_produit) REFERENCES produit(id) ON DELETE CASCADE,
    FOREIGN KEY (id_user)    REFERENCES user(id) ON DELETE CASCADE
);
