<?php

namespace App\Models;

use CodeIgniter\Model;

class ArticleModel extends Model
{
    // --- Configuration principale du Modèle ---

    // Nom de la table en BDD associée à ce modèle
    public $table = 'articles';
    
    // La colonne qui sert de clé primaire
    public $primaryKey = 'id_article';
    
    // Précise que l'ID est généré automatiquement par la BDD
    public $useAutoIncrement = true;
    
    // Les résultats de requêtes seront retournés sous forme d'un tableau (array)
    public $returnType = 'array';

    // Liste des colonnes modifiables. Cela protège contre l'injection de données indésirables (Mass Assignment)
    public $allowedFields = [
        'libelle',
        'description',
        'taille',
        'etat',
        'prix_origine',
        'photo'
    ];

    // --- Règles de validation des données ---
    // Ces règles s'appliquent automatiquement quand on tente d'insérer ou de mettre à jour un article
    public $validationRules = [
        'libelle' => 'required|max_length[255]', // Le libellé est obligatoire (max 255 caractères)
        'prix_origine' => 'required|decimal',  // Le prix doit être renseigné et être un nombre décimal
        'etat' => 'required|in_list[bon,très bon,comme neuf]', // L'état doit obligatoirement être l'une de ces 3 valeurs
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
