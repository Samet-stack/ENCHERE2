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

        // Passer les ventes "à venir" en "en cours"
        $this->where('etat', 'a_venir')
            ->where('date_debut <=', $now)
            ->set('etat', 'en_cours')
            ->update();

        // Passer les ventes "en cours" en "clôturée"
        $this->where('etat', 'en_cours')
            ->where('date_fin <=', $now)
            ->set('etat', 'cloturee')
            ->update();
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
