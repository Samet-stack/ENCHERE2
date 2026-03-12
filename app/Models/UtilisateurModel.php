<?php

namespace App\Models;

use CodeIgniter\Model;

class UtilisateurModel extends Model
{
    // Nom de la table associée à la classe
    public $table = 'utilisateurs';
    
    // Identifiant principal
    public $primaryKey = 'id_utilisateur';
    
    // Auto-incrémentation active
    public $useAutoIncrement = true;
    
    // Retour des requêtes en tableau associatif
    public $returnType = 'array';
    
    // Soft deletes : si true, supprime virtuellement les entrées (colonne deleted_at). Ici désactivé
    public $useSoftDeletes = false;
    
    // Timestamps : si true, gère automatiquement created_at et updated_at. Ici désactivé
    public $useTimestamps = false;

    // Seuls ces champs peuvent être remplis via le modèle
    public $allowedFields = [
        'id_role',
        'nom',
        'prenom',
        'email',
        'mot_de_passe',
        'telephone',
        'adresse',
        'ville',
        'code_postal',
        'est_habitant',
        'est_actif',
        'created_at'
    ];

    public $validationRules = [
        'nom' => 'required|min_length[2]|max_length[100]',
        'prenom' => 'required|min_length[2]|max_length[100]',
        'email' => 'required|valid_email|max_length[255]',
        'mot_de_passe' => 'required|min_length[8]',
    ];

    public $validationMessages = [
        'nom' => [
            'required' => 'Le nom est obligatoire.',
            'min_length' => 'Le nom doit contenir au moins 2 caractères.',
        ],
        'prenom' => [
            'required' => 'Le prénom est obligatoire.',
            'min_length' => 'Le prénom doit contenir au moins 2 caractères.',
        ],
        'email' => [
            'required' => 'L\'email est obligatoire.',
            'valid_email' => 'Veuillez entrer un email valide.',
        ],
        'mot_de_passe' => [
            'required' => 'Le mot de passe est obligatoire.',
            'min_length' => 'Le mot de passe doit contenir au moins 8 caractères.',
        ],
    ];

    /**
     * Récupérer un utilisateur par email
     */
    public function getByEmail(string $email): ?array
    {
        return $this->where('email', $email)->first();
    }

    /**
     * Récupérer un utilisateur avec son rôle
     */
    public function getWithRole(int $id): ?array
    {
        return $this->select('utilisateurs.*, roles.libelle as role_libelle')
            ->join('roles', 'roles.id_role = utilisateurs.id_role')
            ->find($id);
    }

    /**
     * Récupérer tous les utilisateurs d'un rôle donné
     */
    public function getByRole(string $roleLibelle): array
    {
        return $this->select('utilisateurs.*')
            ->join('roles', 'roles.id_role = utilisateurs.id_role')
            ->where('roles.libelle', $roleLibelle)
            ->findAll();
    }
}
