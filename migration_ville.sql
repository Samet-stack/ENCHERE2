-- =====================================================================
-- MIGRATION : Ajout de la colonne 'ville' à la table 'utilisateurs'
-- Pour la restriction géographique (seuls les habitants de Getcet)
-- Date : 08/03/2026
-- =====================================================================

-- Ajouter la colonne 'ville' si elle n'existe pas encore
ALTER TABLE `utilisateurs`
ADD COLUMN IF NOT EXISTS `ville` VARCHAR(100) DEFAULT NULL AFTER `adresse`,
ADD COLUMN IF NOT EXISTS `code_postal` VARCHAR(5) DEFAULT NULL AFTER `ville`;

-- Mettre à jour les utilisateurs existants de Getcet
UPDATE `utilisateurs`
SET `ville` = 'Getcet', `code_postal` = '99999'
WHERE `est_habitant` = 1;
