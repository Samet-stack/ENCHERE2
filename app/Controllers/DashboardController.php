<?php

namespace App\Controllers;

use App\Models\VenteModel;
use App\Models\EnchereModel;
use App\Models\AchatModel;
use App\Models\UtilisateurModel;
use App\Models\ArticleModel;

class DashboardController extends BaseController
{
    /**
     * Tableau de bord du secrétaire
     */
    public function index()
    {
        $venteModel = new VenteModel();
        $enchereModel = new EnchereModel();
        $achatModel = new AchatModel();
        $utilisateurModel = new UtilisateurModel();
        $articleModel = new ArticleModel();

        $venteModel->mettreAJourStatuts();

        // Statistiques
        $ventesParEtat = $venteModel->compterParEtat();
        $statsVentes = ['a_venir' => 0, 'en_cours' => 0, 'cloturee' => 0];
        foreach ($ventesParEtat as $v) {
            $statsVentes[$v['etat']] = $v['total'];
        }

        $totalVentes = array_sum($statsVentes);
        $montantTotal = $achatModel->getMontantTotal();
        $nbUtilisateurs = $utilisateurModel->countAll();
        $nbArticles = $articleModel->countAll();

        // Dernières ventes
        $dernieresVentes = $venteModel->getVentesAvecSecretaire();
        $dernieresVentes = array_slice($dernieresVentes, 0, 10);

        // --- NOUVELLES STATISTIQUES AVANCÉES ---
        $topArticles = $enchereModel->getArticlesPlusEncheris(5);
        $evolutionEncheres = $enchereModel->getEvolutionEncheres(7);
        $tauxParticipation = $venteModel->getTauxParticipation();

        $data = [
            'title' => 'Tableau de bord - EnchèreAPorter',
            'statsVentes' => $statsVentes,
            'totalVentes' => $totalVentes,
            'montantTotal' => $montantTotal,
            'nbUtilisateurs' => $nbUtilisateurs,
            'nbArticles' => $nbArticles,
            'dernieresVentes' => $dernieresVentes,
            'topArticles' => $topArticles,
            'evolutionEncheres' => $evolutionEncheres,
            'tauxParticipation' => $tauxParticipation,
        ];

        return view('dashboard/index', $data);
    }
}
