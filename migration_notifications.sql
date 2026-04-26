-- Table notifications pour les changements de statut de commande
CREATE TABLE IF NOT EXISTS notification (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    id_user    INT NOT NULL,
    contenu    TEXT NOT NULL,
    lu         TINYINT(1) DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES user(id) ON DELETE CASCADE
);
