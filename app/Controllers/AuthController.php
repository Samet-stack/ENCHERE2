<?php

namespace App\Controllers;

use App\Models\UtilisateurModel;
use App\Models\RoleModel;

class AuthController extends BaseController
{
    /**
     * Afficher le formulaire d'inscription
     */
    public function inscription()
    {
        if ($this->session->get('id_utilisateur')) {
            return redirect()->to('/');
        }

        return view('auth/inscription', [
            'title' => 'Inscription - EnchèreAPorter'
        ]);
    }

    /**
     * Traiter l'inscription
     */
    public function inscriptionPost()
    {
        $rules = [
            'nom' => 'required|min_length[2]|max_length[100]',
            'prenom' => 'required|min_length[2]|max_length[100]',
            'email' => 'required|valid_email|is_unique[utilisateurs.email]',
            'mot_de_passe' => 'required|min_length[8]',
            'confirm_password' => 'required|matches[mot_de_passe]',
            'telephone' => 'permit_empty|max_length[20]',
            'adresse' => 'required|max_length[255]',
        ];

        $messages = [
            'email' => [
                'is_unique' => 'Cet email est déjà utilisé.',
            ],
            'confirm_password' => [
                'matches' => 'Les mots de passe ne correspondent pas.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $roleModel = new RoleModel();
        $roleHabitant = $roleModel->where('libelle', 'habitant')->first();

        if (!$roleHabitant) {
            // Créer le rôle s'il n'existe pas
            $roleModel->insert(['libelle' => 'habitant']);
            $roleHabitant = $roleModel->where('libelle', 'habitant')->first();
        }

        $utilisateurModel = new UtilisateurModel();

        $data = [
            'id_role' => $roleHabitant['id_role'],
            'nom' => $this->request->getPost('nom'),
            'prenom' => $this->request->getPost('prenom'),
            'email' => $this->request->getPost('email'),
            'mot_de_passe' => password_hash($this->request->getPost('mot_de_passe'), PASSWORD_DEFAULT),
            'telephone' => $this->request->getPost('telephone'),
            'adresse' => $this->request->getPost('adresse'),
            'est_habitant' => 1,
            'est_actif' => 1,
            'created_at' => date('Y-m-d H:i:s'),
        ];

        $utilisateurModel->insert($data);

        return redirect()->to('/connexion')->with('success', 'Inscription réussie ! Vous pouvez maintenant vous connecter.');
    }

    /**
     * Afficher le formulaire de connexion
     */
    public function connexion()
    {
        if ($this->session->get('id_utilisateur')) {
            return redirect()->to('/');
        }

        return view('auth/connexion', [
            'title' => 'Connexion - EnchèreAPorter'
        ]);
    }

    /**
     * Traiter la connexion
     */
    public function connexionPost()
    {
        $rules = [
            'email' => 'required|valid_email',
            'mot_de_passe' => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $utilisateurModel = new UtilisateurModel();
        $utilisateur = $utilisateurModel->getByEmail($this->request->getPost('email'));

        if (!$utilisateur) {
            return redirect()->back()->withInput()->with('error', 'Email ou mot de passe incorrect.');
        }

        if (!password_verify($this->request->getPost('mot_de_passe'), $utilisateur['mot_de_passe'])) {
            return redirect()->back()->withInput()->with('error', 'Email ou mot de passe incorrect.');
        }

        if (!$utilisateur['est_actif']) {
            return redirect()->back()->with('error', 'Votre compte est désactivé.');
        }

        // Récupérer le rôle
        $roleModel = new RoleModel();
        $role = $roleModel->find($utilisateur['id_role']);

        // Créer la session
        $sessionData = [
            'id_utilisateur' => $utilisateur['id_utilisateur'],
            'nom' => $utilisateur['nom'],
            'prenom' => $utilisateur['prenom'],
            'email' => $utilisateur['email'],
            'role' => $role['libelle'],
            'est_connecte' => true,
        ];

        $this->session->set($sessionData);

        // Rediriger selon le rôle
        if ($role['libelle'] === 'secretaire') {
            return redirect()->to('/dashboard')->with('success', 'Bienvenue ' . $utilisateur['prenom'] . ' !');
        }

        return redirect()->to('/')->with('success', 'Bienvenue ' . $utilisateur['prenom'] . ' !');
    }

    /**
     * Déconnexion
     */
    public function deconnexion()
    {
        $this->session->destroy();
        return redirect()->to('/connexion')->with('success', 'Vous êtes déconnecté.');
    }
}
