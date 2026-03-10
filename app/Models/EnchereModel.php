<?php

namespace App\Models;

use CodeIgniter\Model;

class EnchereModel extends Model
{
    public $table = 'encheres';
    public $primaryKey = 'id_enchere';
    public $useAutoIncrement = true;
    public $returnType = 'array';

    public $allowedFields = [
        'id_vente_article',
        'id_utilisateur',
        'montant',
        'est_annulee',
        'date_enchere'
    ];

    /**
     * Récupérer l'enchère maximale pour un article de vente
     */
    public function getEnchereMax(int $idVenteArticle): ?array
    {
        return $this->where('id_vente_article', $idVenteArticle)
            ->where('est_annulee', 0)
            ->orderBy('montant', 'DESC')
            ->first();
    }

    /**
     * Récupérer le montant maximum actuel
     */
    public function getMontantMax(int $idVenteArticle): float
    {
        $result = $this->selectMax('montant')
            ->where('id_vente_article', $idVenteArticle)
            ->where('est_annulee', 0)
            ->first();

        return $result['montant'] ?? 0;
    }

    /**
     * Récupérer les enchères d'un article de vente
     */
    public function getEncheresArticle(int $idVenteArticle): array
    {
        return $this->select('encheres.*, utilisateurs.nom, utilisateurs.prenom')
            ->join('utilisateurs', 'utilisateurs.id_utilisateur = encheres.id_utilisateur')
            ->where('encheres.id_vente_article', $idVenteArticle)
            ->where('encheres.est_annulee', 0)
            ->orderBy('encheres.montant', 'DESC')
            ->findAll();
    }

    /**
     * Récupérer l'historique des enchères d'un utilisateur
     */
    public function getHistoriqueUtilisateur(int $idUtilisateur): array
    {
        return $this->select('encheres.*, a.libelle as article_libelle, v.titre as vente_titre, va.prix_depart')
            ->join('vente_articles va', 'va.id_vente_article = encheres.id_vente_article')
            ->join('articles a', 'a.id_article = va.id_article')
            ->join('ventes v', 'v.id_vente = va.id_vente')
            ->where('encheres.id_utilisateur', $idUtilisateur)
            ->orderBy('encheres.date_enchere', 'DESC')
            ->findAll();
    }

    /**
     * Récupérer le gagnant pour un article de vente
     */
    public function getGagnant(int $idVenteArticle): ?array
    {
        return $this->select('encheres.*, utilisateurs.nom, utilisateurs.prenom, utilisateurs.email')
            ->join('utilisateurs', 'utilisateurs.id_utilisateur = encheres.id_utilisateur')
            ->where('encheres.id_vente_article', $idVenteArticle)
            ->where('encheres.est_annulee', 0)
            ->orderBy('encheres.montant', 'DESC')
            ->first();
    }

    /**
     * STATISTIQUES : Articles les plus enchéris
     */
    public function getArticlesPlusEncheris(int $limit = 5): array
    {
        return $this->select('a.libelle, COUNT(encheres.id_enchere) as nb_encheres')
            ->join('vente_articles va', 'va.id_vente_article = encheres.id_vente_article')
            ->join('articles a', 'a.id_article = va.id_article')
            ->where('encheres.est_annulee', 0)
            ->groupBy('a.id_article')
            ->orderBy('nb_encheres', 'DESC')
            ->limit($limit)
            ->findAll();
    }

    /**
     * STATISTIQUES : Évolution des enchères (par jour) sur X jours
     */
    public function getEvolutionEncheres(int $jours = 7): array
    {
        return $this->select('DATE(date_enchere) as jour, COUNT(id_enchere) as total')
            ->where('est_annulee', 0)
            ->where('date_enchere >=', date('Y-m-d', strtotime("-$jours days")))
            ->groupBy('DATE(date_enchere)')
            ->orderBy('jour', 'ASC')
            ->findAll();
    }
}
