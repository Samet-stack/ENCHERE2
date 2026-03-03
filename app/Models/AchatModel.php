<?php

namespace App\Models;

use CodeIgniter\Model;

class AchatModel extends Model
{
    protected $table = 'achats';
    protected $primaryKey = 'id_achat';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'id_vente_article',
        'id_utilisateur',
        'id_enchere',
        'montant_final',
        'confirme',
        'date_confirmation'
    ];

    /**
     * Récupérer les achats d'un utilisateur
     */
    public function getAchatsUtilisateur(int $idUtilisateur): array
    {
        return $this->select('achats.*, a.libelle as article_libelle, a.photo, v.titre as vente_titre')
            ->join('vente_articles va', 'va.id_vente_article = achats.id_vente_article')
            ->join('articles a', 'a.id_article = va.id_article')
            ->join('ventes v', 'v.id_vente = va.id_vente')
            ->where('achats.id_utilisateur', $idUtilisateur)
            ->orderBy('achats.id_achat', 'DESC')
            ->findAll();
    }

    /**
     * Montant total des achats confirmés
     */
    public function getMontantTotal(): float
    {
        $result = $this->selectSum('montant_final')
            ->where('confirme', 1)
            ->first();

        return $result['montant_final'] ?? 0;
    }
}
