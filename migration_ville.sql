-- =====================================================================
-- MIGRATION : Ajout de la colonne 'ville' à la table 'utilisateurs'
-- Pour la restriction géographique (seuls les habitants de Getcet)
-- Date : 08/03/2026
-- =====================================================================

-- Ajouter la colonne 'ville' si elle n'existe pas encore
ALTER TABLE `utilisateurs`
ADD COLUMN `ville` VARCHAR(100) DEFAULT NULL AFTER `adresse`;

-- Mettre à jour les utilisateurs existants de Getcet
UPDATE `utilisateurs` SET `ville` = 'Getcet' WHERE `est_habitant` = 1;
