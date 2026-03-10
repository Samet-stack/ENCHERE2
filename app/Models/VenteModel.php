<?php

namespace App\Models;

use CodeIgniter\Model;

class VenteModel extends Model
{
    public $table = 'ventes';
    public $primaryKey = 'id_vente';
    public $useAutoIncrement = true;
    public $returnType = 'array';

    public $allowedFields = [
        'id_secretaire',
        'titre',
        'description',
        'date_debut',
        'date_fin',
        'etat',
        'qrcode',
        'created_at'
    ];

    public $validationRules = [
        'titre' => 'required|max_length[255]',
        'date_debut' => 'required|valid_date',
        'date_fin' => 'required|valid_date',
    ];

    /**
     * Récupérer les ventes avec le nom du secrétaire
     */
    public function getVentesAvecSecretaire(?string $etat = null): array
    {
        $builder = $this->select('ventes.*, utilisateurs.nom as secretaire_nom, utilisateurs.prenom as secretaire_prenom')
            ->join('utilisateurs', 'utilisateurs.id_utilisateur = ventes.id_secretaire');

        if ($etat) {
            $builder->where('ventes.etat', $etat);
        }

        return $builder->orderBy('ventes.date_debut', 'DESC')->findAll();
    }

    /**
     * Récupérer une vente avec détails complets
     */
    public function getVenteDetail(int $id): ?array
    {
        return $this->select('ventes.*, utilisateurs.nom as secretaire_nom, utilisateurs.prenom as secretaire_prenom')
            ->join('utilisateurs', 'utilisateurs.id_utilisateur = ventes.id_secretaire')
            ->find($id);
    }

    /**
     * Mettre à jour les statuts des ventes automatiquement
     */
    public function mettreAJourStatuts(): void
    {
        $now = date('Y-m-d H:i:s');

        // 1. Trouver les ventes qui vont s'ouvrir
        $ventesAOuvrir = $this->where('etat', 'a_venir')
            ->where('date_debut <=', $now)
            ->findAll();

        $inscriptionModel = new \App\Models\InscriptionModel();

        foreach ($ventesAOuvrir as $vente) {
            // Mettre à jour l'état
            $this->update($vente['id_vente'], ['etat' => 'en_cours']);

            // Envoyer le mail d'ouverture aux inscrits
            $inscrits = $inscriptionModel->getInscritsVente($vente['id_vente']);
            foreach ($inscrits as $inscrit) {
                $sujet = "L'enchère '" . $vente['titre'] . "' est ouverte !";
                $message = "Bonjour " . $inscrit['prenom'] . ",<br><br>";
                $message .= "La vente aux enchères <strong>" . $vente['titre'] . "</strong> vient de commencer.<br>";
                $message .= "Vous pouvez dès à présent faire vos offres !<br><br>";
                $message .= "<a href='" . base_url('ventes/' . $vente['id_vente']) . "'>Accéder à la vente</a>";

                \App\Libraries\Mailer::envoyerMail($inscrit['email'], $sujet, $message);
            }
        }

        // 2. Trouver les ventes qui vont se clôturer (le gagnant sera géré via le bouton/fonction clôturer)
        $ventesACloturer = $this->where('etat', 'en_cours')
            ->where('date_fin <=', $now)
            ->findAll();

        foreach ($ventesACloturer as $vente) {
            $this->update($vente['id_vente'], ['etat' => 'cloturee']);
        // NOTE: Le mail aux gagnants sera envoyé explicitement lors de l'appel à la fonction cloturer() du contrôleur 
        // qui gère l'attribution des objets.
        }
    }

    /**
     * Compter les ventes par état
     */
    public function compterParEtat(): array
    {
        return $this->select('etat, COUNT(*) as total')
            ->groupBy('etat')
            ->findAll();
    }

    /**
     * STATISTIQUES : Taux de participation par vente (inscrits vs enchérisseurs)
     */
    public function getTauxParticipation(): array
    {
        // On récupère toutes les ventes clôturées ou en cours
        $ventes = $this->select('id_vente, titre')
            ->whereIn('etat', ['en_cours', 'cloturee'])
            ->orderBy('date_debut', 'DESC')
            ->limit(10)
            ->findAll();

        $db = \Config\Database::connect();

        foreach ($ventes as &$vente) {
            $id = $vente['id_vente'];

            // Nombre d'inscrits à cette vente
            $inscrits = $db->table('inscriptions')->where('id_vente', $id)->countAllResults();

            // Nombre d'utilisateurs distincts ayant enchéri sur cette vente
            $encherisseurs = $db->table('encheres e')
                ->join('vente_articles va', 'va.id_vente_article = e.id_vente_article')
                ->where('va.id_vente', $id)
                ->countAllResults(false);
            // note: pour de vrais utilisateurs uniques: 
            // SELECT COUNT(DISTINCT id_utilisateur) FROM encheres e JOIN vente_articles va ...

            $query = $db->query("SELECT COUNT(DISTINCT e.id_utilisateur) as nb FROM encheres e JOIN vente_articles va ON va.id_vente_article = e.id_vente_article WHERE va.id_vente = ?", [$id]);
            $encherisseurs = $query->getRow()->nb;

            if ($inscrits > 0) {
                $vente['taux'] = round(($encherisseurs / $inscrits) * 100);
            }
            else {
                $vente['taux'] = 0;
            }
        }

        return $ventes;
    }
}
