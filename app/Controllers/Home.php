<?php

namespace App\Controllers;

use App\Models\UtilisateurModel;
use App\Models\RoleModel;
use App\Models\VenteModel;

class Home extends BaseController
{
    public function index()
    {
        $venteModel = new VenteModel();
        $venteModel->mettreAJourStatuts();

        $data = [
            'title' => 'Accueil - EnchèreAPorter',
            'ventesEnCours' => $venteModel->getVentesAvecSecretaire('en_cours'),
            'ventesAVenir' => $venteModel->getVentesAvecSecretaire('a_venir'),
        ];

        return view('accueil', $data);
    }
}
