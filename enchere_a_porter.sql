-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mar. 03 mars 2026 à 22:45
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `enchere_a_porter`
--

-- --------------------------------------------------------

--
-- Structure de la table `achats`
--

CREATE TABLE `achats` (
  `id_achat` int(10) UNSIGNED NOT NULL,
  `id_vente_article` int(10) UNSIGNED NOT NULL,
  `id_utilisateur` int(10) UNSIGNED NOT NULL,
  `id_enchere` int(10) UNSIGNED NOT NULL,
  `montant_final` decimal(10,2) NOT NULL,
  `confirme` tinyint(1) NOT NULL DEFAULT 0,
  `date_confirmation` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `articles`
--

CREATE TABLE `articles` (
  `id_article` int(10) UNSIGNED NOT NULL,
  `libelle` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `taille` varchar(20) DEFAULT NULL,
  `etat` enum('bon','très bon','comme neuf') NOT NULL DEFAULT 'bon',
  `prix_origine` decimal(10,2) NOT NULL,
  `photo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `articles`
--

INSERT INTO `articles` (`id_article`, `libelle`, `description`, `taille`, `etat`, `prix_origine`, `photo`) VALUES
(1, 'test', 'test', 'S', 'bon', 12.00, 'uploads/articles/1772565212_26580a7c3c0487461b64.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `encheres`
--

CREATE TABLE `encheres` (
  `id_enchere` int(10) UNSIGNED NOT NULL,
  `id_vente_article` int(10) UNSIGNED NOT NULL,
  `id_utilisateur` int(10) UNSIGNED NOT NULL,
  `montant` decimal(10,2) NOT NULL,
  `est_annulee` tinyint(1) NOT NULL DEFAULT 0,
  `date_enchere` datetime NOT NULL DEFAULT current_timestamp()
) ;

--
-- Déchargement des données de la table `encheres`
--

INSERT INTO `encheres` (`id_enchere`, `id_vente_article`, `id_utilisateur`, `montant`, `est_annulee`, `date_enchere`) VALUES
(1, 1, 3, 0.20, 0, '2026-03-03 20:15:17'),
(2, 1, 3, 0.60, 0, '2026-03-03 20:15:23');

-- --------------------------------------------------------

--
-- Structure de la table `inscriptions`
--

CREATE TABLE `inscriptions` (
  `id_inscription` int(10) UNSIGNED NOT NULL,
  `id_vente` int(10) UNSIGNED NOT NULL,
  `id_utilisateur` int(10) UNSIGNED NOT NULL,
  `date_inscription` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `mails_log`
--

CREATE TABLE `mails_log` (
  `id_mail` int(10) UNSIGNED NOT NULL,
  `id_vente` int(10) UNSIGNED NOT NULL,
  `type_mail` enum('ouverture','rappel_2h','gagnant') NOT NULL,
  `destinataire` varchar(255) NOT NULL,
  `statut` enum('envoye','echec') NOT NULL DEFAULT 'envoye',
  `envoye_le` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `roles`
--

CREATE TABLE `roles` (
  `id_role` int(10) UNSIGNED NOT NULL,
  `libelle` enum('secretaire','benevole','habitant') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `roles`
--

INSERT INTO `roles` (`id_role`, `libelle`) VALUES
(1, 'secretaire'),
(2, 'benevole'),
(3, 'habitant');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `id_utilisateur` int(10) UNSIGNED NOT NULL,
  `id_role` int(10) UNSIGNED NOT NULL,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `adresse` varchar(255) DEFAULT NULL,
  `est_habitant` tinyint(1) NOT NULL DEFAULT 0,
  `est_actif` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id_utilisateur`, `id_role`, `nom`, `prenom`, `email`, `mot_de_passe`, `telephone`, `adresse`, `est_habitant`, `est_actif`, `created_at`) VALUES
(1, 1, 'Dupont', 'Marie', 'secretaire@getcet.fr', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '01 23 45 67 89', 'Mairie de Getcet', 0, 1, '2026-03-03 20:01:17'),
(2, 2, 'Martin', 'Pierre', 'benevole@getcet.fr', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '01 98 76 54 32', '15 rue des Bénévoles, Getcet', 0, 1, '2026-03-03 20:01:17'),
(3, 3, 'test', 'test', 'test@test.com', '$2y$10$6j2Y1pPD/UG0Cu/4PabyFu/sj5M6f8pdVOKe2MK18vCwuspPLYy1m', 'test', 'test', 1, 1, '2026-03-03 20:05:58');

-- --------------------------------------------------------

--
-- Structure de la table `ventes`
--

CREATE TABLE `ventes` (
  `id_vente` int(10) UNSIGNED NOT NULL,
  `id_secretaire` int(10) UNSIGNED NOT NULL,
  `titre` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `date_debut` datetime NOT NULL,
  `date_fin` datetime NOT NULL,
  `etat` enum('a_venir','en_cours','cloturee') NOT NULL DEFAULT 'a_venir',
  `qrcode` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ;

--
-- Déchargement des données de la table `ventes`
--

INSERT INTO `ventes` (`id_vente`, `id_secretaire`, `titre`, `description`, `date_debut`, `date_fin`, `etat`, `qrcode`, `created_at`) VALUES
(1, 1, 'test', 'test', '2026-03-03 20:12:00', '2026-03-04 09:00:00', 'en_cours', NULL, '2026-03-03 20:11:26');

-- --------------------------------------------------------

--
-- Structure de la table `vente_articles`
--

CREATE TABLE `vente_articles` (
  `id_vente_article` int(10) UNSIGNED NOT NULL,
  `id_vente` int(10) UNSIGNED NOT NULL,
  `id_article` int(10) UNSIGNED NOT NULL,
  `id_benevole` int(10) UNSIGNED NOT NULL,
  `prix_depart` decimal(10,2) NOT NULL DEFAULT 0.20
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `vente_articles`
--

INSERT INTO `vente_articles` (`id_vente_article`, `id_vente`, `id_article`, `id_benevole`, `prix_depart`) VALUES
(1, 1, 1, 1, 0.20);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `achats`
--
ALTER TABLE `achats`
  ADD PRIMARY KEY (`id_achat`),
  ADD UNIQUE KEY `id_vente_article` (`id_vente_article`),
  ADD KEY `id_utilisateur` (`id_utilisateur`),
  ADD KEY `id_enchere` (`id_enchere`);

--
-- Index pour la table `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`id_article`);

--
-- Index pour la table `encheres`
--
ALTER TABLE `encheres`
  ADD PRIMARY KEY (`id_enchere`),
  ADD KEY `id_vente_article` (`id_vente_article`),
  ADD KEY `id_utilisateur` (`id_utilisateur`);

--
-- Index pour la table `inscriptions`
--
ALTER TABLE `inscriptions`
  ADD PRIMARY KEY (`id_inscription`),
  ADD UNIQUE KEY `uq_inscription` (`id_vente`,`id_utilisateur`),
  ADD KEY `id_utilisateur` (`id_utilisateur`);

--
-- Index pour la table `mails_log`
--
ALTER TABLE `mails_log`
  ADD PRIMARY KEY (`id_mail`),
  ADD KEY `id_vente` (`id_vente`);

--
-- Index pour la table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id_role`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id_utilisateur`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `id_role` (`id_role`);

--
-- Index pour la table `ventes`
--
ALTER TABLE `ventes`
  ADD PRIMARY KEY (`id_vente`),
  ADD KEY `id_secretaire` (`id_secretaire`);

--
-- Index pour la table `vente_articles`
--
ALTER TABLE `vente_articles`
  ADD PRIMARY KEY (`id_vente_article`),
  ADD UNIQUE KEY `uq_vente_article` (`id_vente`,`id_article`),
  ADD KEY `id_article` (`id_article`),
  ADD KEY `id_benevole` (`id_benevole`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `achats`
--
ALTER TABLE `achats`
  MODIFY `id_achat` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `articles`
--
ALTER TABLE `articles`
  MODIFY `id_article` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `encheres`
--
ALTER TABLE `encheres`
  MODIFY `id_enchere` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `inscriptions`
--
ALTER TABLE `inscriptions`
  MODIFY `id_inscription` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `mails_log`
--
ALTER TABLE `mails_log`
  MODIFY `id_mail` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `roles`
--
ALTER TABLE `roles`
  MODIFY `id_role` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id_utilisateur` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `ventes`
--
ALTER TABLE `ventes`
  MODIFY `id_vente` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `vente_articles`
--
ALTER TABLE `vente_articles`
  MODIFY `id_vente_article` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `achats`
--
ALTER TABLE `achats`
  ADD CONSTRAINT `achats_ibfk_1` FOREIGN KEY (`id_vente_article`) REFERENCES `vente_articles` (`id_vente_article`),
  ADD CONSTRAINT `achats_ibfk_2` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id_utilisateur`),
  ADD CONSTRAINT `achats_ibfk_3` FOREIGN KEY (`id_enchere`) REFERENCES `encheres` (`id_enchere`);

--
-- Contraintes pour la table `encheres`
--
ALTER TABLE `encheres`
  ADD CONSTRAINT `encheres_ibfk_1` FOREIGN KEY (`id_vente_article`) REFERENCES `vente_articles` (`id_vente_article`),
  ADD CONSTRAINT `encheres_ibfk_2` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id_utilisateur`);

--
-- Contraintes pour la table `inscriptions`
--
ALTER TABLE `inscriptions`
  ADD CONSTRAINT `inscriptions_ibfk_1` FOREIGN KEY (`id_vente`) REFERENCES `ventes` (`id_vente`),
  ADD CONSTRAINT `inscriptions_ibfk_2` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id_utilisateur`);

--
-- Contraintes pour la table `mails_log`
--
ALTER TABLE `mails_log`
  ADD CONSTRAINT `mails_log_ibfk_1` FOREIGN KEY (`id_vente`) REFERENCES `ventes` (`id_vente`);

--
-- Contraintes pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD CONSTRAINT `utilisateurs_ibfk_1` FOREIGN KEY (`id_role`) REFERENCES `roles` (`id_role`);

--
-- Contraintes pour la table `ventes`
--
ALTER TABLE `ventes`
  ADD CONSTRAINT `ventes_ibfk_1` FOREIGN KEY (`id_secretaire`) REFERENCES `utilisateurs` (`id_utilisateur`);

--
-- Contraintes pour la table `vente_articles`
--
ALTER TABLE `vente_articles`
  ADD CONSTRAINT `vente_articles_ibfk_1` FOREIGN KEY (`id_vente`) REFERENCES `ventes` (`id_vente`),
  ADD CONSTRAINT `vente_articles_ibfk_2` FOREIGN KEY (`id_article`) REFERENCES `articles` (`id_article`),
  ADD CONSTRAINT `vente_articles_ibfk_3` FOREIGN KEY (`id_benevole`) REFERENCES `utilisateurs` (`id_utilisateur`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
