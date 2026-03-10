<?php

namespace App\Models;

use CodeIgniter\Model;

class InscriptionModel extends Model
{
    public $table = 'inscriptions';
    public $primaryKey = 'id_inscription';
    public $useAutoIncrement = true;
    public $returnType = 'array';

    public $allowedFields = [
        'id_vente',
        'id_utilisateur',
        'date_inscription'
    ];

    /**
     * Vérifier si un utilisateur est inscrit à une vente
     */
    public function estInscrit(int $idVente, int $idUtilisateur): bool
    {
        return $this->where('id_vente', $idVente)
            ->where('id_utilisateur', $idUtilisateur)
            ->countAllResults() > 0;
    }

    /**
     * Récupérer les inscrits d'une vente
     */
    public function getInscritsVente(int $idVente): array
    {
        return $this->select('inscriptions.*, utilisateurs.nom, utilisateurs.prenom, utilisateurs.email')
            ->join('utilisateurs', 'utilisateurs.id_utilisateur = inscriptions.id_utilisateur')
            ->where('inscriptions.id_vente', $idVente)
            ->findAll();
    }
}
