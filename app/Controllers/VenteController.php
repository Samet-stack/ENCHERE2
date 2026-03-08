<?php

namespace App\Controllers;

use App\Models\VenteModel;
use App\Models\ArticleModel;
use App\Models\VenteArticleModel;
use App\Models\InscriptionModel;
use App\Models\EnchereModel;
use App\Models\AchatModel;

class VenteController extends BaseController
{
    /**
     * Liste des ventes
     */
    public function index()
    {
        $venteModel = new VenteModel();
        $venteModel->mettreAJourStatuts();

        $etat = $this->request->getGet('etat');

        $data = [
            'title' => 'Ventes - EnchèreAPorter',
            'ventes' => $venteModel->getVentesAvecSecretaire($etat),
            'filtre' => $etat,
        ];

        return view('ventes/index', $data);
    }

    /**
     * Détail d'une vente
     */
    public function detail($id)
    {
        $venteModel = new VenteModel();
        $articleModel = new ArticleModel();
        $inscriptionModel = new InscriptionModel();

        $venteModel->mettreAJourStatuts();

        $vente = $venteModel->getVenteDetail($id);

        if (!$vente) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Vente non trouvée');
        }

        $articles = $articleModel->getArticlesVente($id);

        $estInscrit = false;
        if ($this->session->get('id_utilisateur')) {
            $estInscrit = $inscriptionModel->estInscrit($id, $this->session->get('id_utilisateur'));
        }

        $data = [
            'title' => $vente['titre'] . ' - EnchèreAPorter',
            'vente' => $vente,
            'articles' => $articles,
            'estInscrit' => $estInscrit,
            'inscrits' => $inscriptionModel->getInscritsVente($id),
        ];

        return view('ventes/detail', $data);
    }

    /**
     * Formulaire de création d'une vente (secrétaire)
     */
    public function creer()
    {
        return view('ventes/creer', [
            'title' => 'Créer une vente - EnchèreAPorter'
        ]);
    }

    /**
     * Traiter la création d'une vente
     */
    public function creerPost()
    {
        $rules = [
            'titre' => 'required|max_length[255]',
            'description' => 'permit_empty',
            'date_debut' => 'required|valid_date',
            'date_fin' => 'required|valid_date',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $venteModel = new VenteModel();

        $data = [
            'id_secretaire' => $this->session->get('id_utilisateur'),
            'titre' => $this->request->getPost('titre'),
            'description' => $this->request->getPost('description'),
            'date_debut' => $this->request->getPost('date_debut'),
            'date_fin' => $this->request->getPost('date_fin'),
            'etat' => 'a_venir',
            'created_at' => date('Y-m-d H:i:s'),
        ];

        $venteModel->insert($data);

        return redirect()->to('/ventes')->with('success', 'Vente créée avec succès !');
    }

    /**
     * S'inscrire à une vente
     */
    public function inscrire($idVente)
    {
        $inscriptionModel = new InscriptionModel();

        if ($inscriptionModel->estInscrit($idVente, $this->session->get('id_utilisateur'))) {
            return redirect()->back()->with('error', 'Vous êtes déjà inscrit à cette vente.');
        }

        $inscriptionModel->insert([
            'id_vente' => $idVente,
            'id_utilisateur' => $this->session->get('id_utilisateur'),
        ]);

        return redirect()->back()->with('success', 'Inscription réussie !');
    }

    /**
     * Clôturer une vente et attribuer les gagnants
     */
    public function cloturer($idVente)
    {
        $venteModel = new VenteModel();
        $venteArticleModel = new VenteArticleModel();
        $enchereModel = new EnchereModel();
        $achatModel = new AchatModel();

        $vente = $venteModel->find($idVente);

        if (!$vente) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Vente non trouvée');
        }

        // Mettre à jour le statut
        $venteModel->update($idVente, ['etat' => 'cloturee']);

        // Récupérer tous les articles de cette vente
        $articlesVente = $venteArticleModel->where('id_vente', $idVente)->findAll();

        foreach ($articlesVente as $va) {
            // Trouver l'enchère gagnante avec infos utilisateur
            $enchereGagnante = $enchereModel->getGagnant($va['id_vente_article']);

            if ($enchereGagnante) {
                $achatModel->insert([
                    'id_vente_article' => $va['id_vente_article'],
                    'id_utilisateur' => $enchereGagnante['id_utilisateur'],
                    'id_enchere' => $enchereGagnante['id_enchere'],
                    'montant_final' => $enchereGagnante['montant'],
                    'confirme' => 0,
                ]);

                // Envoyer l'email au gagnant
                $sujet = "Félicitations ! Vous avez remporté l'enchère pour '" . $vente['titre'] . "'";
                $message = "Bonjour " . $enchereGagnante['prenom'] . ",<br><br>";
                $message .= "Excellente nouvelle ! Vous avez remporté l'enchère sur l'un des articles de la vente <strong>" . $vente['titre'] . "</strong>.<br>";
                $message .= "Montant final : <strong>" . number_format($enchereGagnante['montant'], 2) . " €</strong>.<br><br>";
                $message .= "Veuillez vous connecter à votre espace pour confirmer votre achat.<br><br>";
                $message .= "<a href='" . base_url('achats') . "'>Voir mes achats</a>";

                \App\Libraries\Mailer::envoyerMail($enchereGagnante['email'], $sujet, $message);
            }
        }

        return redirect()->to('/ventes/' . $idVente)->with('success', 'Vente clôturée ! Les gagnants ont été désignés et notifiés par e-mail.');
    }

    /**
     * Générer un QR Code pour une vente
     */
    public function qrcode($idVente)
    {
        $venteModel = new VenteModel();
        $vente = $venteModel->find($idVente);

        if (!$vente) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Vente non trouvée');
        }

        $url = base_url('ventes/' . $idVente);

        // Utiliser l'API Google Charts pour générer un QR Code
        $qrCodeUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . urlencode($url);

        $data = [
            'title' => 'QR Code - ' . $vente['titre'],
            'vente' => $vente,
            'qrCodeUrl' => $qrCodeUrl,
            'venteUrl' => $url,
        ];

        return view('ventes/qrcode', $data);
    }
}
