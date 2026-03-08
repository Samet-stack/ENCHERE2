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
        // A venir -> En cours
        $db->query("UPDATE ventes SET etat='en_cours' WHERE etat='a_venir' AND date_debut <= '$now'");

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
}
