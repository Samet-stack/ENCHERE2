<?php

namespace App\Controllers;

use App\Models\UtilisateurModel;

class ProfilController extends BaseController
{
    /**
     * Afficher le profil
     */
    public function index()
    {
        $utilisateurModel = new UtilisateurModel();
        $utilisateur = $utilisateurModel->getWithRole($this->session->get('id_utilisateur'));

        $data = [
            'title' => 'Mon profil - EnchèreAPorter',
            'utilisateur' => $utilisateur,
        ];

        return view('profil/index', $data);
    }

    /**
     * Modifier le profil
     */
    public function modifier()
    {
        $rules = [
            'nom' => 'required|min_length[2]|max_length[100]',
            'prenom' => 'required|min_length[2]|max_length[100]',
            'telephone' => 'permit_empty|max_length[20]',
            'adresse' => 'permit_empty|max_length[255]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $utilisateurModel = new UtilisateurModel();

        $data = [
            'nom' => $this->request->getPost('nom'),
            'prenom' => $this->request->getPost('prenom'),
            'telephone' => $this->request->getPost('telephone'),
            'adresse' => $this->request->getPost('adresse'),
        ];

        // Changement de mot de passe optionnel
        $newPassword = $this->request->getPost('nouveau_mot_de_passe');
        if (!empty($newPassword)) {
            if (strlen($newPassword) < 8) {
                return redirect()->back()->with('error', 'Le nouveau mot de passe doit contenir au moins 8 caractères.');
            }
            $data['mot_de_passe'] = password_hash($newPassword, PASSWORD_DEFAULT);
        }

        $utilisateurModel->update($this->session->get('id_utilisateur'), $data);

        // Mettre à jour la session
        $this->session->set([
            'nom' => $data['nom'],
            'prenom' => $data['prenom'],
        ]);

        return redirect()->to('/profil')->with('success', 'Profil mis à jour !');
    }
}
