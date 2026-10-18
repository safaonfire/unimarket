-- Ajouter la colonne created_at à la table user pour les statistiques d'inscriptions
ALTER TABLE `user`
  ADD COLUMN `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP;
