<?php

namespace App\Controllers;

use App\Models\ArticleModel;
use App\Models\VenteModel;
use App\Models\VenteArticleModel;

class ArticleController extends BaseController
{
    /**
     * Liste des articles
     */
    public function index()
    {
        $articleModel = new ArticleModel();

        $data = [
            'title' => 'Articles - EnchèreAPorter',
            'articles' => $articleModel->findAll(),
        ];

        return view('articles/index', $data);
    }

    /**
     * Formulaire de création d'article
     */
    public function creer()
    {
        return view('articles/creer', [
            'title' => 'Ajouter un article - EnchèreAPorter'
        ]);
    }

    /**
     * Traiter la création d'un article
     */
    public function creerPost()
    {
        $rules = [
            'libelle' => 'required|max_length[255]',
            'description' => 'permit_empty',
            'taille' => 'permit_empty|max_length[20]',
            'etat' => 'required|in_list[bon,très bon,comme neuf]',
            'prix_origine' => 'required|decimal',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $articleModel = new ArticleModel();

        $data = [
            'libelle' => $this->request->getPost('libelle'),
            'description' => $this->request->getPost('description'),
            'taille' => $this->request->getPost('taille'),
            'etat' => $this->request->getPost('etat'),
            'prix_origine' => $this->request->getPost('prix_origine'),
        ];

        // Gestion de la photo
        $photo = $this->request->getFile('photo');
        if ($photo && $photo->isValid() && !$photo->hasMoved()) {
            $newName = $photo->getRandomName();
            $photo->move(FCPATH . 'uploads/articles/', $newName);
            $data['photo'] = 'uploads/articles/' . $newName;
        }

        $articleModel->insert($data);

        return redirect()->to('/articles')->with('success', 'Article ajouté avec succès !');
    }

    /**
     * Sélectionner un article pour une vente (bénévole)
     */
    public function selectionner($idVente)
    {
        $rules = [
            'id_article' => 'required|integer',
            'prix_depart' => 'required|decimal|greater_than_equal_to[0.20]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $venteArticleModel = new VenteArticleModel();

        // Vérifier si l'article n'est pas déjà dans cette vente
        $existe = $venteArticleModel->where('id_vente', $idVente)
            ->where('id_article', $this->request->getPost('id_article'))
            ->first();

        if ($existe) {
            return redirect()->back()->with('error', 'Cet article est déjà dans cette vente.');
        }

        $venteArticleModel->insert([
            'id_vente' => $idVente,
            'id_article' => $this->request->getPost('id_article'),
            'id_benevole' => $this->session->get('id_utilisateur'),
            'prix_depart' => $this->request->getPost('prix_depart'),
        ]);

        return redirect()->to('/ventes/' . $idVente)->with('success', 'Article ajouté à la vente !');
    }
}
