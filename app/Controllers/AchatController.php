<?php

namespace App\Controllers;

use App\Models\AchatModel;

class AchatController extends BaseController
{
    /**
     * Liste des achats de l'utilisateur
     */
    public function index()
    {
        $achatModel = new AchatModel();

        $data = [
            'title' => 'Mes achats - EnchèreAPorter',
            'achats' => $achatModel->getAchatsUtilisateur($this->session->get('id_utilisateur')),
        ];

        return view('achats/index', $data);
    }

    /**
     * Confirmer un achat
     */
    public function confirmer($idAchat)
    {
        $achatModel = new AchatModel();
        $achat = $achatModel->find($idAchat);

        if (!$achat) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Achat non trouvé');
        }

        if ($achat['id_utilisateur'] != $this->session->get('id_utilisateur')) {
            return redirect()->back()->with('error', 'Cet achat ne vous appartient pas.');
        }

        $achatModel->update($idAchat, [
            'confirme' => 1,
            'date_confirmation' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/achats')->with('success', 'Achat confirmé !');
    }
}
