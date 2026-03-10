# 🏷️ EnchèreAPorter - Ville de Getcet

> *« Clique vite sinon c’est ton voisin qui le porte ! »*

**EnchèreAPorter** est une application web développée avec le framework **CodeIgniter 4** permettant la vente aux enchères de vêtements de seconde main invendus de la friperie municipale *"Fripouilles"*, située dans la ville de Getcet.

---

## 📖 Présentation du projet

Initiative lancée par la Mairie de Getcet pour donner une seconde vie aux vêtements invendus, cette plateforme permet :
- De financer des actions locales pour la ville.
- De participer à une démarche écologique (upcycling / seconde main).
- Aux habitants d'enchérir en ligne sur des pièces uniques depuis chez eux.

---

## 🚀 Fonctionnalités principales (Modules)

L'application est divisée en plusieurs modules selon les rôles des utilisateurs :

### 🏛️ Rôle "Secrétaire de Mairie" (Administrateur)
- **Gestion des enchères** : Création des ventes en précisant les dates de début et de fin.
- **Suivi** : Consultation des enchères pendant et après la vente.
- **Statistiques** : Accès à un tableau de bord global de l'activité.
- **Accessibilité** : Création de **QR Codes** pour accéder directement à une vente spécifique.
- **Notifications (Mails)** : Envoi automatique d'emails pour l'ouverture, 2h avant la clôture, et attribution au gagnant une fois la vente terminée.

### 🤝 Rôle "Bénévole"
- **Sélection des articles** : Ajout d'articles à une vente (avec définition d'un prix de départ minimum de 0.20€).
- **Consultation** : Accès aux résultats des enchères **uniquement après la clôture** de la vente (véritable verrouillage métier).

### 🏠 Rôle "Habitant / Acheteur"
- **Inscription stricte** : Réservée exclusivement aux habitants de Getcet (vérification stricte du code postal `99999`).
- **Sécurité** : Obligation d'utiliser un mot de passe fort lors de la création du compte (8 caractères, 1 majuscule, 1 minuscule, 1 chiffre, 1 caractère spécial).
- **Participation** : Inscription aux ventes à venir et participation active aux enchères en cours.
- **Suivi & Achats** : Consultation de l'historique de ses enchères, annulation possible (si applicable), et confirmation des achats remportés avec génération d'un **reçu d'achat**.

---

## 🛠️ Stack Technique & Architecture

- **Architecture** : MVC (Modèle-Vue-Contrôleur)
- **Framework Backend** : [CodeIgniter 4](https://codeigniter.com/) (PHP)
- **Base de données** : MySQL / MariaDB
- **Sécurité** :
  - **CSRF** activé globalement sur tous les formulaires POST.
  - **Filtre d'authentification (`AuthFilter`)** bloquant l'accès aux pages privées.
  - Contrôle d'accès rigoureux coté serveur selon les rôles (`RoleFilter` et conditions dynamiques).

---

## 📋 Prérequis et Installation (Local)

1. Cloner le dépôt :
   ```bash
   git clone https://github.com/Samet-stack/codeigniter4-school-system.git
   ```
2. Importer la base de données :
   - Importer le fichier sql (ex: `enchere_a_porter.sql` ou `seed.sql`) dans PhpMyAdmin.
3. Configurer l'environnement :
   - Modifier le fichier `.env` pour pointer vers la base de données locale.
   - S'assurer que `app.baseURL` correspond bien à l'adresse URL locale du projet (ex: `http://localhost/MonProjet/public/`).
4. Lancer le serveur local (WAMP/XAMPP/Laragon) ou utiliser le serveur web intégré de CodeIgniter :
   ```bash
   php spark serve
   ```

---

*Projet réalisé dans le cadre de l'examen BTS SIO (Production d’un système opérationnel).*
