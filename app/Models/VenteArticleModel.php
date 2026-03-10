<?php

namespace App\Models;

use CodeIgniter\Model;

class VenteArticleModel extends Model
{
    public $table = 'vente_articles';
    public $primaryKey = 'id_vente_article';
    public $useAutoIncrement = true;
    public $returnType = 'array';

    public $allowedFields = [
        'id_vente',
        'id_article',
        'id_benevole',
        'prix_depart'
    ];

    public $validationRules = [
        'prix_depart' => 'required|decimal|greater_than_equal_to[0.20]',
    ];

    /**
     * Récupérer un article de vente avec toutes les infos
     */
    public function getVenteArticleDetail(int $id): ?array
    {
        return $this->db->table('vente_articles va')
            ->select('va.*, a.libelle, a.description, a.taille, a.etat, a.prix_origine, a.photo,
                      v.titre as vente_titre, v.date_fin as vente_date_fin, v.etat as vente_etat,
                      u.nom as benevole_nom, u.prenom as benevole_prenom')
            ->join('articles a', 'a.id_article = va.id_article')
            ->join('ventes v', 'v.id_vente = va.id_vente')
            ->join('utilisateurs u', 'u.id_utilisateur = va.id_benevole')
            ->where('va.id_vente_article', $id)
            ->get()
            ->getRowArray();
    }
}
