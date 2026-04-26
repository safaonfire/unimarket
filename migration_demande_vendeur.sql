CREATE TABLE IF NOT EXISTS demande_vendeur (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    id_user    INT NOT NULL,
    message    TEXT,
    statut     ENUM('en_attente','acceptee','refusee') DEFAULT 'en_attente',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES user(id) ON DELETE CASCADE
);
