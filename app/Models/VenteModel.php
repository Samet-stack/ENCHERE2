<?php

namespace App\Models;

use CodeIgniter\Model;

class VenteModel extends Model
{
    protected $table = 'ventes';
    protected $primaryKey = 'id_vente';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'id_secretaire',
        'titre',
        'description',
        'date_debut',
        'date_fin',
        'etat',
        'qrcode',
        'created_at'
    ];

    protected $validationRules = [
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
}
