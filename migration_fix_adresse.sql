-- BUG FIX #13: rendre la colonne adresse nullable
-- La colonne était NOT NULL mais le formulaire d'inscription ne la rend pas obligatoire.
-- Cette migration évite l'erreur SQL lors d'une inscription sans adresse.
ALTER TABLE `user`
  MODIFY `adresse` varchar(255) NOT NULL DEFAULT '';
