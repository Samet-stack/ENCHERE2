<?php

namespace App\Controllers;

use App\Models\EnchereModel;
use App\Models\VenteArticleModel;

class EnchereController extends BaseController
{
    /**
     * Placer une enchère
     */
    public function encherir($idVenteArticle)
    {
        $enchereModel = new EnchereModel();
        $venteArticleModel = new VenteArticleModel();

        // Vérifier que l'article de vente existe
        $venteArticle = $venteArticleModel->getVenteArticleDetail($idVenteArticle);

        if (!$venteArticle) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Article non trouvé');
        }

        // Vérifier que la vente est en cours
        if ($venteArticle['vente_etat'] !== 'en_cours') {
            return redirect()->back()->with('error', 'Cette vente n\'est pas en cours.');
        }

        $montant = (float) $this->request->getPost('montant');
        $montantMax = $enchereModel->getMontantMax($idVenteArticle);

        // Première enchère : minimum = prix_depart (0.20€ minimum)
        $minimum = max($venteArticle['prix_depart'], 0.20);

        if ($montantMax > 0) {
            // Enchères suivantes : minimum = enchère max + 0.10€
            $minimum = $montantMax + 0.10;
        }

        if ($montant < $minimum) {
            return redirect()->back()->with('error', 'Le montant minimum est de ' . number_format($minimum, 2) . ' €.');
        }

        $enchereModel->insert([
            'id_vente_article' => $idVenteArticle,
            'id_utilisateur' => $this->session->get('id_utilisateur'),
            'montant' => $montant,
            'est_annulee' => 0,
            'date_enchere' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/ventes/' . $venteArticle['id_vente'])->with('success', 'Enchère placée avec succès ! (' . number_format($montant, 2) . ' €)');
    }

    /**
     * Annuler une enchère
     */
    public function annuler($idEnchere)
    {
        $enchereModel = new EnchereModel();

        $enchere = $enchereModel->find($idEnchere);

        if (!$enchere) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Enchère non trouvée');
        }

        // Vérifier que l'enchère appartient à l'utilisateur
        if ($enchere['id_utilisateur'] != $this->session->get('id_utilisateur')) {
            return redirect()->back()->with('error', 'Vous ne pouvez pas annuler cette enchère.');
        }

        $enchereModel->update($idEnchere, ['est_annulee' => 1]);

        return redirect()->back()->with('success', 'Enchère annulée.');
    }

    /**
     * Historique des enchères de l'utilisateur
     */
    public function historique()
    {
        $enchereModel = new EnchereModel();

        $data = [
            'title' => 'Historique des enchères - EnchèreAPorter',
            'encheres' => $enchereModel->getHistoriqueUtilisateur($this->session->get('id_utilisateur')),
        ];

        return view('encheres/historique', $data);
    }
}
