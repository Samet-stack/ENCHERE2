<?php

namespace App\Models;

use CodeIgniter\Model;

class EnchereModel extends Model
{
    protected $table = 'encheres';
    protected $primaryKey = 'id_enchere';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
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
}
