<?php

namespace App\Models;

use CodeIgniter\Model;

class ArticleModel extends Model
{
    protected $table = 'articles';
    protected $primaryKey = 'id_article';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'libelle',
        'description',
        'taille',
        'etat',
        'prix_origine',
        'photo'
    ];

    protected $validationRules = [
        'libelle' => 'required|max_length[255]',
        'prix_origine' => 'required|decimal',
        'etat' => 'required|in_list[bon,très bon,comme neuf]',
    ];

    /**
     * Récupérer les articles non encore assignés à une vente
     */
    public function getArticlesDisponibles(): array
    {
        return $this->select('articles.*')
            ->join('vente_articles', 'vente_articles.id_article = articles.id_article', 'left')
            ->where('vente_articles.id_vente_article IS NULL')
            ->findAll();
    }

    /**
     * Récupérer les articles d'une vente avec enchère max
     */
    public function getArticlesVente(int $idVente): array
    {
        return $this->db->table('vente_articles va')
            ->select('va.*, a.*, 
                      (SELECT MAX(e.montant) FROM encheres e WHERE e.id_vente_article = va.id_vente_article AND e.est_annulee = 0) as enchère_max,
                      (SELECT COUNT(e.id_enchere) FROM encheres e WHERE e.id_vente_article = va.id_vente_article AND e.est_annulee = 0) as nb_encheres')
            ->join('articles a', 'a.id_article = va.id_article')
            ->where('va.id_vente', $idVente)
            ->get()
            ->getResultArray();
    }
}
