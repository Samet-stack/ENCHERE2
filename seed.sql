-- ============================================================
--  EnchèreAPorter - Données initiales (Seed)
-- ============================================================

USE enchere_a_porter;

-- Insérer les rôles si non existants
INSERT IGNORE INTO roles (id_role, libelle) VALUES
(1, 'secretaire'),
(2, 'benevole'),
(3, 'habitant');

-- Créer un compte secrétaire de test
-- Mot de passe: admin123 (hashé avec password_hash)
INSERT IGNORE INTO utilisateurs (id_utilisateur, id_role, nom, prenom, email, mot_de_passe, telephone, adresse, est_habitant, est_actif, created_at) VALUES
(1, 1, 'Dupont', 'Marie', 'secretaire@getcet.fr', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '01 23 45 67 89', 'Mairie de Getcet', 0, 1, NOW());

-- Créer un compte bénévole de test
INSERT IGNORE INTO utilisateurs (id_utilisateur, id_role, nom, prenom, email, mot_de_passe, telephone, adresse, est_habitant, est_actif, created_at) VALUES
(2, 2, 'Martin', 'Pierre', 'benevole@getcet.fr', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '01 98 76 54 32', '15 rue des Bénévoles, Getcet', 0, 1, NOW());
