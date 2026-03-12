<?php
namespace App\Models;
use CodeIgniter\Model;

class Modele extends Model
{
    // ==================== ROLES ====================
    public function getLesRoles()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('roles');
        $builder->select('*');
        $query = $builder->get();
        $db->close();
        return $query->getResult();
    }

    public function getRoleParId($id)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('roles');
        $builder->where('id_role', $id);
        $query = $builder->get();
        $db->close();
        return $query->getRow();
    }

    // ==================== UTILISATEURS ====================
    public function getUtilisateurParEmail($email)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('utilisateurs');
        $builder->where('email', $email);
        $query = $builder->get();
        $db->close();
        return $query->getRow();
    }

    public function getUtilisateurParId($id)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('utilisateurs');
        $builder->select('utilisateurs.*, roles.libelle as role_libelle');
        $builder->join('roles', 'roles.id_role = utilisateurs.id_role');
        $builder->where('id_utilisateur', $id);
        $query = $builder->get();
        $db->close();
        return $query->getRow();
    }

    public function insertUtilisateur($data)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('utilisateurs');
        $builder->insert($data);
        $db->close();
    }

    public function updateUtilisateur($id, $data)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('utilisateurs');
        $builder->where('id_utilisateur', $id);
        $builder->update($data);
        $db->close();
    }

    public function getNbUtilisateurs()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('utilisateurs');
        $builder->selectCount('id_utilisateur', 'nb');
        $query = $builder->get();
        $result = $query->getRow();
        $db->close();
        return $result->nb;
    }

    public function getTousLesHabitants()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('utilisateurs');
        $builder->where('id_role', 3); // 3 = habitant
        $builder->where('est_actif', 1);
        $query = $builder->get();
        $db->close();
        return $query->getResult();
    }

    // ==================== ARTICLES ====================
    public function getLesArticles()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('articles');
        $builder->select('*');
        $query = $builder->get();
        $db->close();
        return $query->getResult();
    }

    public function getArticlesDisponibles()
    {
        $db = \Config\Database::connect();
        $sql = "SELECT a.* FROM articles a LEFT JOIN vente_articles va ON va.id_article = a.id_article WHERE va.id_vente_article IS NULL";
        $query = $db->query($sql);
        $db->close();
        return $query->getResult();
    }

    public function insertArticle($data)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('articles');
        $builder->insert($data);
        $db->close();
    }

    public function getNbArticles()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('articles');
        $builder->selectCount('id_article', 'nb');
        $query = $builder->get();
        $result = $query->getRow();
        $db->close();
        return $result->nb;
    }

    public function getArticleParId($id)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('articles');
        $builder->where('id_article', $id);
        $query = $builder->get();
        $db->close();
        return $query->getRow();
    }

    public function supprimerArticle($id)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('articles');
        $builder->where('id_article', $id);
        $builder->delete();
        $db->close();
    }

    // ==================== VENTES ====================
    public function getLesVentes($etat = null)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('ventes');
        $builder->select('ventes.*, utilisateurs.nom as secretaire_nom, utilisateurs.prenom as secretaire_prenom');
        $builder->join('utilisateurs', 'utilisateurs.id_utilisateur = ventes.id_secretaire');
        if ($etat) {
            $builder->where('ventes.etat', $etat);
        }
        $builder->orderBy('ventes.date_debut', 'DESC');
        $query = $builder->get();
        $db->close();
        return $query->getResult();
    }

    public function getVenteParId($id)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('ventes');
        $builder->select('ventes.*, utilisateurs.nom as secretaire_nom, utilisateurs.prenom as secretaire_prenom');
        $builder->join('utilisateurs', 'utilisateurs.id_utilisateur = ventes.id_secretaire');
        $builder->where('id_vente', $id);
        $query = $builder->get();
        $db->close();
        return $query->getRow();
    }

    public function insertVente($data)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('ventes');
        $builder->insert($data);
        $db->close();
    }

    public function updateVente($id, $data)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('ventes');
        $builder->where('id_vente', $id);
        $builder->update($data);
        $db->close();
    }

    public function mettreAJourStatutsVentes()
    {
        $db = \Config\Database::connect();
        $now = date('Y-m-d H:i:s');
        // A venir -> En cours + envoi des mails d'ouverture
        $queryAOuvrir = $db->query("SELECT id_vente, titre FROM ventes WHERE etat='a_venir' AND date_debut <= '$now'");
        $ventesAOuvrir = $queryAOuvrir->getResult();

        foreach ($ventesAOuvrir as $v) {
            // Passage de la vente en cours
            $db->query("UPDATE ventes SET etat='en_cours' WHERE id_vente = " . (int) $v->id_vente);

            // Mail d'ouverture aux inscrits (éviter les doublons via mails_log)
            $inscrits = $this->getInscritsVente($v->id_vente);
            $titreVente = htmlspecialchars((string) $v->titre, ENT_QUOTES, 'UTF-8');
            $sujet = "Ouverture des enchères : " . $v->titre;
            $message = "<h1>Les enchères sont ouvertes !</h1>";
            $message .= "<p>La vente <strong>" . $titreVente . "</strong> est maintenant en cours.</p>";
            $message .= "<p>Connectez-vous pour placer vos enchères.</p>";

            foreach ($inscrits as $inscrit) {
                $dejaEnvoye = $db->query(
                    "SELECT id_mail FROM mails_log WHERE id_vente = ? AND type_mail = 'ouverture' AND destinataire = ? LIMIT 1",
                    [$v->id_vente, $inscrit->email]
                )->getRow();

                if (!$dejaEnvoye) {
                    \App\Libraries\Mailer::envoyerMail($inscrit->email, $sujet, $message);
                    $this->logMail($v->id_vente, 'ouverture', $inscrit->email, 'envoye', $db);
                }
            }
        }

        // Gestion des Mails de rappel (2 heures avant la clôture)
        $queryRappel = $db->query("SELECT id_vente, titre, date_fin FROM ventes WHERE etat='en_cours' AND TIMESTAMPDIFF(HOUR, '$now', date_fin) <= 2");
        $ventesARappeler = $queryRappel->getResult();
        foreach ($ventesARappeler as $v) {
            // Vérifier si le mail n'a pas déjà été envoyé pour cette vente
            $dejaEnvoye = $db->query("SELECT id_mail FROM mails_log WHERE id_vente = " . $v->id_vente . " AND type_mail = 'rappel_2h'")->getRow();
            if (!$dejaEnvoye) {
                $inscrits = $this->getInscritsVente($v->id_vente);
                $sujet = "Rappel : L'enchère " . $v->titre . " se termine bientôt !";
                $message = "<h1>Attention !</h1><p>La vente <strong>" . $v->titre . "</strong> se termine dans moins de 2 heures (" . date('H:i', strtotime($v->date_fin)) . ").</p>";

                foreach ($inscrits as $inscrit) {
                    \App\Libraries\Mailer::envoyerMail($inscrit->email, $sujet, $message);
                    $this->logMail($v->id_vente, 'rappel_2h', $inscrit->email, 'envoye', $db);
                }
            }
        }

        // En cours -> Clôturée
        $query = $db->query("SELECT id_vente FROM ventes WHERE etat='en_cours' AND date_fin <= '$now'");
        $ventesACloturer = $query->getResult();

        foreach ($ventesACloturer as $v) {
            $this->cloturerVenteLogique($v->id_vente, $db);
        }

        $db->close();
    }

    public function cloturerVenteLogique($idVente, $db = null)
    {
        $closeDb = false;
        if ($db === null) {
            $db = \Config\Database::connect();
            $closeDb = true;
        }

        $db->query("UPDATE ventes SET etat='cloturee' WHERE id_vente = $idVente");

        // Attribuer les gagnants
        $articlesVente = $this->getVenteArticlesParVente($idVente);
        foreach ($articlesVente as $va) {
            $enchereGagnante = $this->getEnchereMax($va->id_vente_article);
            if ($enchereGagnante) {
                // Vérifier que l'achat n'existe pas déjà pour éviter les doublons
                $achatExistant = $db->query("SELECT id_achat FROM achats WHERE id_vente_article = " . $va->id_vente_article)->getRow();
                if (!$achatExistant) {
                    $this->insertAchat([
                        'id_vente_article' => $va->id_vente_article,
                        'id_utilisateur' => $enchereGagnante->id_utilisateur,
                        'id_enchere' => $enchereGagnante->id_enchere,
                        'montant_final' => $enchereGagnante->montant,
                        'confirme' => 0,
                    ]);

                    // Code duplication for logic closure (often called by chron process)
                    $article = $this->getArticleParId($va->id_article);
                    $sujet = "Vous avez remporté l'enchère : " . $article->libelle;
                    $message = "<h1>Félicitations " . $enchereGagnante->prenom . " !</h1>";
                    $message .= "<p>Vous avez remporté l'enchère pour l'article <strong>" . $article->libelle . "</strong> avec une offre de <strong>" . $enchereGagnante->montant . " €</strong>.</p>";
                    $message .= "<p>Merci de vous connecter pour confirmer votre achat.</p>";

                    \App\Libraries\Mailer::envoyerMail($enchereGagnante->email, $sujet, $message);
                    $this->logMail($idVente, 'gagnant', $enchereGagnante->email, 'envoye', $db);
                }
            }
        }

        if ($closeDb) {
            $db->close();
        }
    }

    public function compterVentesParEtat()
    {
        $db = \Config\Database::connect();
        $sql = "SELECT etat, COUNT(*) as total FROM ventes GROUP BY etat";
        $query = $db->query($sql);
        $db->close();
        return $query->getResult();
    }

    // ==================== VENTE_ARTICLES ====================
    public function getArticlesDeVente($idVente)
    {
        $db = \Config\Database::connect();
        $sql = "SELECT va.*, a.libelle, a.description, a.taille, a.etat, a.prix_origine, a.photo,
                (SELECT MAX(e.montant) FROM encheres e WHERE e.id_vente_article = va.id_vente_article AND e.est_annulee = 0) as enchere_max,
                (SELECT COUNT(e.id_enchere) FROM encheres e WHERE e.id_vente_article = va.id_vente_article AND e.est_annulee = 0) as nb_encheres
                FROM vente_articles va
                JOIN articles a ON a.id_article = va.id_article
                WHERE va.id_vente = $idVente";
        $query = $db->query($sql);
        $db->close();
        return $query->getResult();
    }

    public function getVenteArticleDetail($id)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('vente_articles va');
        $builder->select('va.*, a.libelle, a.description, a.taille, a.etat, a.prix_origine, a.photo, v.titre as vente_titre, v.date_debut as vente_date_debut, v.date_fin as vente_date_fin, v.etat as vente_etat, v.id_vente');
        $builder->join('articles a', 'a.id_article = va.id_article');
        $builder->join('ventes v', 'v.id_vente = va.id_vente');
        $builder->where('va.id_vente_article', $id);
        $query = $builder->get();
        $db->close();
        return $query->getRow();
    }

    public function insertVenteArticle($data)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('vente_articles');
        $builder->insert($data);
        $db->close();
    }

    public function venteArticleExiste($idVente, $idArticle)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('vente_articles');
        $builder->where('id_vente', $idVente);
        $builder->where('id_article', $idArticle);
        $query = $builder->get();
        $db->close();
        return $query->getRow() ? true : false;
    }

    public function getVenteArticlesParVente($idVente)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('vente_articles');
        $builder->where('id_vente', $idVente);
        $query = $builder->get();
        $db->close();
        return $query->getResult();
    }

    // ==================== INSCRIPTIONS ====================
    public function estInscrit($idVente, $idUtilisateur)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('inscriptions');
        $builder->where('id_vente', $idVente);
        $builder->where('id_utilisateur', $idUtilisateur);
        $query = $builder->get();
        $db->close();
        return $query->getRow() ? true : false;
    }

    public function insertInscription($data)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('inscriptions');
        $builder->insert($data);
        $db->close();
    }

    public function getInscritsVente($idVente)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('inscriptions');
        $builder->select('inscriptions.*, utilisateurs.nom, utilisateurs.prenom, utilisateurs.email');
        $builder->join('utilisateurs', 'utilisateurs.id_utilisateur = inscriptions.id_utilisateur');
        $builder->where('inscriptions.id_vente', $idVente);
        $query = $builder->get();
        $db->close();
        return $query->getResult();
    }

    // ==================== ENCHERES ====================
    public function getMontantMax($idVenteArticle)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('encheres');
        $builder->selectMax('montant');
        $builder->where('id_vente_article', $idVenteArticle);
        $builder->where('est_annulee', 0);
        $query = $builder->get();
        $result = $query->getRow();
        $db->close();
        return $result->montant ?? 0;
    }

    public function getEnchereMax($idVenteArticle)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('encheres');
        $builder->select('encheres.*, utilisateurs.nom, utilisateurs.prenom, utilisateurs.email');
        $builder->join('utilisateurs', 'utilisateurs.id_utilisateur = encheres.id_utilisateur');
        $builder->where('encheres.id_vente_article', $idVenteArticle);
        $builder->where('encheres.est_annulee', 0);
        $builder->orderBy('encheres.montant', 'DESC');
        $builder->limit(1);
        $query = $builder->get();
        $db->close();
        return $query->getRow();
    }

    public function insertEnchere($data)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('encheres');
        $builder->insert($data);
        $db->close();
    }

    public function getEnchereParId($id)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('encheres');
        $builder->where('id_enchere', $id);
        $query = $builder->get();
        $db->close();
        return $query->getRow();
    }

    public function annulerEnchere($id)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('encheres');
        $builder->where('id_enchere', $id);
        $builder->update(['est_annulee' => 1]);
        $db->close();
    }

    public function getHistoriqueEncheres($idUtilisateur)
    {
        $db = \Config\Database::connect();
        $sql = "SELECT e.*, a.libelle as article_libelle, v.titre as vente_titre, v.etat as vente_etat, va.prix_depart
                FROM encheres e
                JOIN vente_articles va ON va.id_vente_article = e.id_vente_article
                JOIN articles a ON a.id_article = va.id_article
                JOIN ventes v ON v.id_vente = va.id_vente
                WHERE e.id_utilisateur = $idUtilisateur
                ORDER BY e.date_enchere DESC";
        $query = $db->query($sql);
        $db->close();
        return $query->getResult();
    }

    // ==================== ACHATS ====================
    public function insertAchat($data)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('achats');
        $builder->insert($data);
        $db->close();
    }

    public function getAchatsUtilisateur($idUtilisateur)
    {
        $db = \Config\Database::connect();
        $sql = "SELECT ac.*, a.libelle as article_libelle, a.photo, v.titre as vente_titre
                FROM achats ac
                JOIN vente_articles va ON va.id_vente_article = ac.id_vente_article
                JOIN articles a ON a.id_article = va.id_article
                JOIN ventes v ON v.id_vente = va.id_vente
                WHERE ac.id_utilisateur = $idUtilisateur
                ORDER BY ac.id_achat DESC";
        $query = $db->query($sql);
        $db->close();
        return $query->getResult();
    }

    public function getAchatParId($id)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('achats');
        $builder->where('id_achat', $id);
        $query = $builder->get();
        $db->close();
        return $query->getRow();
    }

    public function confirmerAchat($id)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('achats');
        $builder->where('id_achat', $id);
        $builder->update(['confirme' => 1, 'date_confirmation' => date('Y-m-d H:i:s')]);
        $db->close();
    }

    public function getMontantTotalAchats()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('achats');
        $builder->selectSum('montant_final');
        $builder->where('confirme', 1);
        $query = $builder->get();
        $result = $query->getRow();
        $db->close();
        return $result->montant_final ?? 0;
    }

    // ==================== MAILS LOG ====================
    public function logMail($idVente, $typeMail, $destinataire, $statut, $db = null)
    {
        $closeDb = false;
        if ($db === null) {
            $db = \Config\Database::connect();
            $closeDb = true;
        }

        $builder = $db->table('mails_log');
        $builder->insert([
            'id_vente' => $idVente,
            'type_mail' => $typeMail,
            'destinataire' => $destinataire,
            'statut' => $statut,
            'envoye_le' => date('Y-m-d H:i:s')
        ]);

        if ($closeDb) {
            $db->close();
        }
    }

    // ==================== STATISTIQUES AVANCÉES ====================

    /**
     * Récupère les articles les plus enchéris (TOP N par nombre d'enchères).
     * Utilisé dans le dashboard du secrétaire.
     */
    public function getArticlesPlusEncheris($limit = 5)
    {
        $db = \Config\Database::connect();
        $sql = "SELECT a.libelle, COUNT(e.id_enchere) as nb_encheres
                FROM encheres e
                JOIN vente_articles va ON va.id_vente_article = e.id_vente_article
                JOIN articles a ON a.id_article = va.id_article
                WHERE e.est_annulee = 0
                GROUP BY a.id_article, a.libelle
                ORDER BY nb_encheres DESC
                LIMIT $limit";
        $query = $db->query($sql);
        $db->close();
        return $query->getResult();
    }

    /**
     * Récupère l'évolution des enchères par jour sur les X derniers jours.
     * Utilisé pour le graphique dans le dashboard du secrétaire.
     */
    public function getEvolutionEncheres($jours = 7)
    {
        $db = \Config\Database::connect();
        $dateDebut = date('Y-m-d', strtotime("-$jours days"));
        $sql = "SELECT DATE(date_enchere) as jour, COUNT(id_enchere) as total
                FROM encheres
                WHERE est_annulee = 0
                AND date_enchere >= '$dateDebut'
                GROUP BY DATE(date_enchere)
                ORDER BY jour ASC";
        $query = $db->query($sql);
        $db->close();
        return $query->getResult();
    }

    /**
     * Taux de participation : combien d'inscrits ont réellement enchéri ?
     */
    public function getTauxParticipation()
    {
        $db = \Config\Database::connect();

        // Les 10 dernières ventes actives ou finies
        $ventes = $db->query("SELECT id_vente, titre FROM ventes
            WHERE etat IN ('en_cours', 'cloturee')
            ORDER BY date_debut DESC LIMIT 10")->getResult();

        foreach ($ventes as &$v) {
            // Combien de gens inscrits ?
            $inscrits = $db->query("SELECT COUNT(*) as nb FROM inscriptions WHERE id_vente = ?", [$v->id_vente])->getRow()->nb;

            // Combien ont enchéri (utilisateurs uniques) ?
            $actifs = $db->query("SELECT COUNT(DISTINCT e.id_utilisateur) as nb
                FROM encheres e JOIN vente_articles va ON va.id_vente_article = e.id_vente_article
                WHERE va.id_vente = ? AND e.est_annulee = 0", [$v->id_vente])->getRow()->nb;

            // Calcul du taux (si personne inscrit = 0%)
            $v->taux = ($inscrits > 0) ? round(($actifs / $inscrits) * 100) : 0;
        }

        $db->close();
        return $ventes;
    }

    // ==================== REÇU D'ACHAT ====================

    /**
     * Détails complets d'un achat (article + vente + acheteur) pour le reçu.
     */
    public function getAchatDetail($idAchat)
    {
        $db = \Config\Database::connect();
        $sql = "SELECT ac.*, a.libelle as article_libelle, a.description as article_description,
                a.taille, a.etat as article_etat, a.prix_origine, a.photo,
                v.titre as vente_titre, v.date_debut as vente_date_debut, v.date_fin as vente_date_fin,
                u.nom as acheteur_nom, u.prenom as acheteur_prenom, u.email as acheteur_email,
                u.adresse as acheteur_adresse, u.telephone as acheteur_telephone
                FROM achats ac
                JOIN vente_articles va ON va.id_vente_article = ac.id_vente_article
                JOIN articles a ON a.id_article = va.id_article
                JOIN ventes v ON v.id_vente = va.id_vente
                JOIN utilisateurs u ON u.id_utilisateur = ac.id_utilisateur
                WHERE ac.id_achat = ?";
        $query = $db->query($sql, [$idAchat]);
        $db->close();
        return $query->getRow();
    }
}
