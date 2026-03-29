SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS `achats`;
CREATE TABLE `achats` (
  `id_achat` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_vente_article` int(10) unsigned NOT NULL,
  `id_utilisateur` int(10) unsigned NOT NULL,
  `id_enchere` int(10) unsigned NOT NULL,
  `montant_final` decimal(10,2) NOT NULL,
  `confirme` tinyint(1) NOT NULL DEFAULT 0,
  `date_confirmation` datetime DEFAULT NULL,
  PRIMARY KEY (`id_achat`),
  UNIQUE KEY `id_vente_article` (`id_vente_article`),
  KEY `id_utilisateur` (`id_utilisateur`),
  KEY `id_enchere` (`id_enchere`),
  CONSTRAINT `achats_ibfk_1` FOREIGN KEY (`id_vente_article`) REFERENCES `vente_articles` (`id_vente_article`),
  CONSTRAINT `achats_ibfk_2` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id_utilisateur`),
  CONSTRAINT `achats_ibfk_3` FOREIGN KEY (`id_enchere`) REFERENCES `encheres` (`id_enchere`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
LOCK TABLES `achats` WRITE;
INSERT INTO `achats` VALUES (1,1,3,2,0.60,0,NULL);
UNLOCK TABLES;

DROP TABLE IF EXISTS `articles`;
CREATE TABLE `articles` (
  `id_article` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `libelle` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `taille` varchar(20) DEFAULT NULL,
  `etat` enum('bon','très bon','comme neuf') NOT NULL DEFAULT 'bon',
  `prix_origine` decimal(10,2) NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_article`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
LOCK TABLES `articles` WRITE;
INSERT INTO `articles` VALUES (1,'test','test','S','bon',12.00,'uploads/articles/1772565212_26580a7c3c0487461b64.jpg'),(2,'polat','efzef','S','très bon',29.99,'uploads/articles/1773262370_07ed8b4ee2046f7102c1.png');
UNLOCK TABLES;

DROP TABLE IF EXISTS `encheres`;
CREATE TABLE `encheres` (
  `id_enchere` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_vente_article` int(10) unsigned NOT NULL,
  `id_utilisateur` int(10) unsigned NOT NULL,
  `montant` decimal(10,2) NOT NULL,
  `est_annulee` tinyint(1) NOT NULL DEFAULT 0,
  `date_enchere` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_enchere`),
  KEY `id_vente_article` (`id_vente_article`),
  KEY `id_utilisateur` (`id_utilisateur`),
  CONSTRAINT `encheres_ibfk_1` FOREIGN KEY (`id_vente_article`) REFERENCES `vente_articles` (`id_vente_article`),
  CONSTRAINT `encheres_ibfk_2` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id_utilisateur`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
LOCK TABLES `encheres` WRITE;
INSERT INTO `encheres` VALUES (1,1,3,0.20,0,'2026-03-03 20:15:17'),(2,1,3,0.60,0,'2026-03-03 20:15:23');
UNLOCK TABLES;

DROP TABLE IF EXISTS `inscriptions`;
CREATE TABLE `inscriptions` (
  `id_inscription` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_vente` int(10) unsigned NOT NULL,
  `id_utilisateur` int(10) unsigned NOT NULL,
  `date_inscription` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_inscription`),
  UNIQUE KEY `uq_inscription` (`id_vente`,`id_utilisateur`),
  KEY `id_utilisateur` (`id_utilisateur`),
  CONSTRAINT `inscriptions_ibfk_1` FOREIGN KEY (`id_vente`) REFERENCES `ventes` (`id_vente`),
  CONSTRAINT `inscriptions_ibfk_2` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id_utilisateur`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
LOCK TABLES `inscriptions` WRITE;
UNLOCK TABLES;

DROP TABLE IF EXISTS `mails_log`;
CREATE TABLE `mails_log` (
  `id_mail` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_vente` int(10) unsigned NOT NULL,
  `type_mail` enum('ouverture','rappel_2h','gagnant') NOT NULL,
  `destinataire` varchar(255) NOT NULL,
  `statut` enum('envoye','echec') NOT NULL DEFAULT 'envoye',
  `envoye_le` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_mail`),
  KEY `id_vente` (`id_vente`),
  CONSTRAINT `mails_log_ibfk_1` FOREIGN KEY (`id_vente`) REFERENCES `ventes` (`id_vente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
LOCK TABLES `mails_log` WRITE;
UNLOCK TABLES;

DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `id_role` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `libelle` enum('secretaire','benevole','habitant') NOT NULL,
  PRIMARY KEY (`id_role`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
LOCK TABLES `roles` WRITE;
INSERT INTO `roles` VALUES (1,'secretaire'),(2,'benevole'),(3,'habitant');
UNLOCK TABLES;

DROP TABLE IF EXISTS `utilisateurs`;
CREATE TABLE `utilisateurs` (
  `id_utilisateur` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_role` int(10) unsigned NOT NULL,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `adresse` varchar(255) DEFAULT NULL,
  `est_habitant` tinyint(1) NOT NULL DEFAULT 0,
  `est_actif` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_utilisateur`),
  UNIQUE KEY `email` (`email`),
  KEY `id_role` (`id_role`),
  CONSTRAINT `utilisateurs_ibfk_1` FOREIGN KEY (`id_role`) REFERENCES `roles` (`id_role`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
LOCK TABLES `utilisateurs` WRITE;
INSERT INTO `utilisateurs` VALUES (1,1,'Dupont','Marie','secretaire@getcet.fr','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','01 23 45 67 89','Mairie de Getcet',0,1,'2026-03-03 20:01:17'),(2,2,'Martin','Pierre','benevole@getcet.fr','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','01 98 76 54 32','15 rue des Bénévoles, Getcet',0,1,'2026-03-03 20:01:17'),(3,3,'test','test','test@test.com','$2y$10$6j2Y1pPD/UG0Cu/4PabyFu/sj5M6f8pdVOKe2MK18vCwuspPLYy1m','test','test',1,1,'2026-03-03 20:05:58'),(4,3,'Test','User','testuser@example.com','$2y$10$OXEnydQ3PDnUATE22QtCWerQYX2.XJ9uNbdIjQVOw0SablTHkKqzW','0612345678','123 Rue de Getcet',1,1,'2026-03-11 12:20:39');
UNLOCK TABLES;

DROP TABLE IF EXISTS `vente_articles`;
CREATE TABLE `vente_articles` (
  `id_vente_article` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_vente` int(10) unsigned NOT NULL,
  `id_article` int(10) unsigned NOT NULL,
  `id_benevole` int(10) unsigned NOT NULL,
  `prix_depart` decimal(10,2) NOT NULL DEFAULT 0.20,
  PRIMARY KEY (`id_vente_article`),
  UNIQUE KEY `uq_vente_article` (`id_vente`,`id_article`),
  KEY `id_article` (`id_article`),
  KEY `id_benevole` (`id_benevole`),
  CONSTRAINT `vente_articles_ibfk_1` FOREIGN KEY (`id_vente`) REFERENCES `ventes` (`id_vente`),
  CONSTRAINT `vente_articles_ibfk_2` FOREIGN KEY (`id_article`) REFERENCES `articles` (`id_article`),
  CONSTRAINT `vente_articles_ibfk_3` FOREIGN KEY (`id_benevole`) REFERENCES `utilisateurs` (`id_utilisateur`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
LOCK TABLES `vente_articles` WRITE;
INSERT INTO `vente_articles` VALUES (1,1,1,1,0.20);
UNLOCK TABLES;

DROP TABLE IF EXISTS `ventes`;
CREATE TABLE `ventes` (
  `id_vente` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_secretaire` int(10) unsigned NOT NULL,
  `titre` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `date_debut` datetime NOT NULL,
  `date_fin` datetime NOT NULL,
  `etat` enum('a_venir','en_cours','cloturee') NOT NULL DEFAULT 'a_venir',
  `qrcode` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_vente`),
  KEY `id_secretaire` (`id_secretaire`),
  CONSTRAINT `ventes_ibfk_1` FOREIGN KEY (`id_secretaire`) REFERENCES `utilisateurs` (`id_utilisateur`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
LOCK TABLES `ventes` WRITE;
INSERT INTO `ventes` VALUES (1,1,'test','test','2026-03-03 20:12:00','2026-03-04 09:00:00','cloturee',NULL,'2026-03-03 20:11:26');
UNLOCK TABLES;

SET FOREIGN_KEY_CHECKS=1;
