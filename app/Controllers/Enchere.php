<?php
namespace App\Controllers;

class Enchere extends BaseController
{
    private const VILLE_HABITANT = 'Getcet';
    private const CODE_POSTAL_HABITANT = '99999';

    private function normaliserVille(?string $ville): string
    {
        return ucfirst(strtolower(trim((string) $ville)));
    }

    private function estAdresseHabitantValide(?string $ville, ?string $codePostal): bool
    {
        return $this->normaliserVille($ville) === self::VILLE_HABITANT
            && trim((string) $codePostal) === self::CODE_POSTAL_HABITANT;
    }
    //  INITIALISATION (vérification session) 
    public function init()
    {
        $session = session();
        if (!$session->get('estConnecte')) {
            return false;
        }
        $data = [
            'titre' => 'EnchèreAPorter',
            'session' => $session,
        ];
        return $data;
    }

    // ACCUEIL
    public function index()
    {
        $monmodel = new \App\Models\Modele();
        $monmodel->mettreAJourStatutsVentes();

        $ventesEnCours = $monmodel->getLesVentes('en_cours');
        $ventesAVenir = $monmodel->getLesVentes('a_venir');

        if (session()->get('role') === 'benevole') {
            $ventesEnCours = [];
            $ventesAVenir = [];
        }

        $data = [
            'titre' => 'Accueil - EnchèreAPorter',
            'session' => session(),
            'ventesEnCours' => $ventesEnCours,
            'ventesAVenir' => $ventesAVenir,
        ];
        return view('accueil', $data);
    }

    // TEMPORAIRE : à supprimer après utilisation
    public function resetPassword()
    {
        $db = \Config\Database::connect();
        $hash = \password_hash('admin123', PASSWORD_DEFAULT);
        $builder = $db->table('utilisateurs');
        $builder->whereIn('id_utilisateur', [1, 2]);
        $builder->update(['mot_de_passe' => $hash]);
        $db->close();
        echo "Mots de passe réinitialisés avec le hash : " . $hash;
        echo "<br><br><a href='" . base_url('Enchere/connexion') . "'>Aller à la connexion</a>";
    }

    // INSCRIPTION
    public function inscription()
    {
        $session = session();
        if ($session->get('estConnecte')) {
            return redirect()->to('Enchere/index');
        }
        return view('inscription', ['titre' => 'Inscription - EnchèreAPorter']);
    }

    public function validerInscription()
    {
        // DEBUG : vérifie que la méthode est bien appelée
        log_message('debug', '=== INSCRIPTION : Méthode appelée ===');

        $rules = [
            'nom' => 'required|min_length[2]|max_length[100]',
            'prenom' => 'required|min_length[2]|max_length[100]',
            'email' => 'required|valid_email|is_unique[utilisateurs.email]',
            'mot_de_passe' => [
                'rules' => 'required|min_length[8]|regex_match[/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/]',
                'errors' => [
                    'regex_match' => 'Le mot de passe doit contenir au moins 1 majuscule, 1 minuscule, 1 chiffre et 1 caractère spécial.'
                ]
            ],
            'confirm_password' => 'required|matches[mot_de_passe]',
            'adresse' => 'required|max_length[255]',
            'ville' => 'required|min_length[2]|max_length[100]',
            'code_postal' => 'required|exact_length[5]',
        ];

        if (!$this->validate($rules)) {
            log_message('debug', '=== INSCRIPTION : Validation échouée ===');
            return view('inscription', [
                'titre' => 'Inscription - EnchèreAPorter',
                'erreur' => 'Erreur de validation : ' . \implode(', ', $this->validator->getErrors()),
            ]);
        }

        // Vérification que le code postal correspond à la ville de Getcet
        $codePostal = $this->request->getVar('code_postal');
        $ville = $this->request->getVar('ville');
        if (!$this->estAdresseHabitantValide($ville, $codePostal)) {
            return view('inscription', [
                'titre' => 'Inscription - EnchèreAPorter',
                'erreur' => 'Seuls les habitants de Getcet (code postal 99999) peuvent s\'inscrire.',
            ]);
        }

        $monmodel = new \App\Models\Modele();

        $data = [
            'id_role' => 3, // habitant
            'nom' => $this->request->getVar('nom'),
            'prenom' => $this->request->getVar('prenom'),
            'email' => $this->request->getVar('email'),
            'mot_de_passe' => \password_hash($this->request->getVar('mot_de_passe'), PASSWORD_DEFAULT),
            'telephone' => $this->request->getVar('telephone'),
            'adresse' => $this->request->getVar('adresse'),
            'ville' => self::VILLE_HABITANT,
            'code_postal' => self::CODE_POSTAL_HABITANT,
            'est_habitant' => 1,
            'est_actif' => 1,
            'created_at' => \date('Y-m-d H:i:s'),
        ];

        try {
            $monmodel->insertUtilisateur($data);
            log_message('debug', '=== INSCRIPTION : INSERT réussi ===');
        } catch (\Exception $e) {
            log_message('error', '=== INSCRIPTION : Erreur INSERT === ' . $e->getMessage());
            return view('inscription', [
                'titre' => 'Inscription - EnchèreAPorter',
                'erreur' => 'Erreur base de données : ' . $e->getMessage(),
            ]);
        }
        return redirect()->to('Enchere/connexion');
    }

    // CONNEXION
    public function connexion()
    {
        $session = session();
        if ($session->get('estConnecte')) {
            return redirect()->to('Enchere/index');
        }
        return view('connexion', ['titre' => 'Connexion - EnchèreAPorter']);
    }

    public function connecter()
    {
        $email = $this->request->getVar('email');
        $mdp = $this->request->getVar('mot_de_passe');

        $monmodel = new \App\Models\Modele();
        $utilisateur = $monmodel->getUtilisateurParEmail($email);

        if ($utilisateur && \password_verify($mdp, $utilisateur->mot_de_passe)) {
            if (!$utilisateur->est_actif) {
                return view('connexion', ['titre' => 'Connexion', 'erreur' => 'Compte désactivé.']);
            }
            $role = $monmodel->getRoleParId($utilisateur->id_role);

            $session = session();
            $session->set('estConnecte', true);
            $session->set('id_utilisateur', $utilisateur->id_utilisateur);
            $session->set('nom', $utilisateur->nom);
            $session->set('prenom', $utilisateur->prenom);
            $session->set('email', $utilisateur->email);
            $session->set('role', $role->libelle);

            if ($role->libelle === 'secretaire') {
                return redirect()->to('Enchere/dashboard');
            }
            return redirect()->to('Enchere/index');
        } else {
            return view('connexion', ['titre' => 'Connexion', 'erreur' => 'Email ou mot de passe incorrect.']);
        }
    }

    public function deconnexion()
    {
        $session = session();
        $session->destroy();
        return redirect()->to(base_url('Enchere/connexion'));
    }

    // VENTES
    public function listeVentes()
    {
        $monmodel = new \App\Models\Modele();
        $monmodel->mettreAJourStatutsVentes();

        $etat = $this->request->getVar('etat');

        // Un bénévole ne peut voir que les ventes clôturées
        if (session()->get('role') === 'benevole') {
            $etat = 'cloturee';
        }

        $data = [
            'titre' => 'Ventes - EnchèreAPorter',
            'session' => session(),
            'ventes' => $monmodel->getLesVentes($etat),
            'filtre' => $etat,
        ];
        return view('liste_ventes', $data);
    }

    public function detailVente($id)
    {
        $monmodel = new \App\Models\Modele();
        $monmodel->mettreAJourStatutsVentes();

        $vente = $monmodel->getVenteParId($id);
        if (!$vente) {
            return redirect()->to('Enchere/listeVentes');
        }

        $articles = $monmodel->getArticlesDeVente($id);
        $inscrits = $monmodel->getInscritsVente($id);

        $estInscrit = false;
        $session = session();
        if ($session->get('id_utilisateur')) {
            $estInscrit = $monmodel->estInscrit($id, $session->get('id_utilisateur'));
        }

        // Articles disponibles pour sélection (bénévole/secrétaire)
        $articlesDisponibles = $monmodel->getArticlesDisponibles();

        if ($session->get('role') === 'benevole' && $vente->etat !== 'cloturee') {
            foreach ($articles as $article) {
                $article->enchere_max = null;
                $article->nb_encheres = null;
            }
        }

        $data = [
            'titre' => $vente->titre . ' - EnchèreAPorter',
            'session' => $session,
            'vente' => $vente,
            'articles' => $articles,
            'estInscrit' => $estInscrit,
            'inscrits' => $inscrits,
            'articlesDisponibles' => $articlesDisponibles,
        ];
        return view('detail_vente', $data);
    }

    public function creerVente()
    {
        $data = $this->init();
        if (!$data)
            return redirect()->to(base_url('Enchere/connexion'));
        $session = session();
        if ($session->get('role') !== 'secretaire') {
            return redirect()->to('Enchere/index');
        }
        return view('creer_vente', $data);
    }

    public function validerCreerVente()
    {
        $data = $this->init();
        if (!$data)
            return redirect()->to(base_url('Enchere/connexion'));

        $rules = [
            'titre' => 'required|max_length[255]',
            'date_debut' => 'required',
            'date_fin' => 'required',
        ];

        if (!$this->validate($rules)) {
            return view('creer_vente', $data);
        }

        $monmodel = new \App\Models\Modele();
        $session = session();

        $venteData = [
            'id_secretaire' => $session->get('id_utilisateur'),
            'titre' => $this->request->getVar('titre'),
            'description' => $this->request->getVar('description'),
            'date_debut' => $this->request->getVar('date_debut'),
            'date_fin' => $this->request->getVar('date_fin'),
            'etat' => 'a_venir',
            'created_at' => date('Y-m-d H:i:s'),
        ];

        $monmodel->insertVente($venteData);
        return redirect()->to('Enchere/listeVentes');
    }

    public function inscrireVente($idVente)
    {
        $data = $this->init();
        if (!$data)
            return redirect()->to(base_url('Enchere/connexion'));

        $monmodel = new \App\Models\Modele();
        $session = session();

        // Seuls les habitants peuvent s'inscrire aux ventes
        if ($session->get('role') !== 'habitant') {
            return redirect()->to('Enchere/detailVente/' . $idVente);
        }

        $vente = $monmodel->getVenteParId($idVente);
        if (!$vente) {
            return redirect()->to('Enchere/listeVentes');
        }

        if ($vente->etat !== 'a_venir' || strtotime($vente->date_debut) <= time()) {
            return redirect()->to('Enchere/detailVente/' . $idVente);
        }

        if (!$monmodel->estInscrit($idVente, $session->get('id_utilisateur'))) {
            $monmodel->insertInscription([
                'id_vente' => $idVente,
                'id_utilisateur' => $session->get('id_utilisateur'),
            ]);
        }
        return redirect()->to('Enchere/detailVente/' . $idVente);
    }

    public function cloturerVente($idVente)
    {
        $data = $this->init();
        if (!$data)
            return redirect()->to(base_url('Enchere/connexion'));
        $session = session();
        if ($session->get('role') !== 'secretaire') {
            return redirect()->to('Enchere/index');
        }

        $monmodel = new \App\Models\Modele();
        $vente = $monmodel->getVenteParId($idVente);
        $monmodel->updateVente($idVente, ['etat' => 'cloturee']);

        // Attribuer les gagnants
        $articlesVente = $monmodel->getVenteArticlesParVente($idVente);
        foreach ($articlesVente as $va) {
            $enchereGagnante = $monmodel->getEnchereMax($va->id_vente_article);
            if ($enchereGagnante) {
                $monmodel->insertAchat([
                    'id_vente_article' => $va->id_vente_article,
                    'id_utilisateur' => $enchereGagnante->id_utilisateur,
                    'id_enchere' => $enchereGagnante->id_enchere,
                    'montant_final' => $enchereGagnante->montant,
                    'confirme' => 0,
                ]);

                // Notifier le gagnant par email
                $article = $monmodel->getArticleParId($va->id_article);
                $venteTitre = $vente ? htmlspecialchars((string) $vente->titre, ENT_QUOTES, 'UTF-8') : 'la vente';
                $articleLibelle = $article ? htmlspecialchars((string) $article->libelle, ENT_QUOTES, 'UTF-8') : 'un article';

                $sujet = "Felicitation ! Vous avez remporte l'enchere : " . ($article ? $article->libelle : 'Article');
                $message = "<h1>Felicitation " . htmlspecialchars((string) $enchereGagnante->prenom, ENT_QUOTES, 'UTF-8') . " !</h1>";
                $message .= "<p>Vous avez remporte l'enchere pour <strong>" . $articleLibelle . "</strong> dans <strong>" . $venteTitre . "</strong>.</p>";
                $message .= "<p>Montant final : <strong>" . number_format($enchereGagnante->montant, 2) . " EUR</strong>.</p>";
                $message .= "<p>Connectez-vous a votre espace pour confirmer votre achat.</p>";

                \App\Libraries\Mailer::envoyerMail($enchereGagnante->email, $sujet, $message);
                $monmodel->logMail($idVente, 'gagnant', $enchereGagnante->email, 'envoye');
            }
        }
        return redirect()->to('Enchere/detailVente/' . $idVente);
    }

    public function qrcodeVente($idVente)
    {
        $data = $this->init();
        if (!$data)
            return redirect()->to(base_url('Enchere/connexion'));

        $monmodel = new \App\Models\Modele();
        $vente = $monmodel->getVenteParId($idVente);
        if (!$vente)
            return redirect()->to('Enchere/listeVentes');

        $url = base_url('Enchere/detailVente/' . $idVente);
        $qrCodeUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . urlencode($url);

        $data['vente'] = $vente;
        $data['qrCodeUrl'] = $qrCodeUrl;
        $data['venteUrl'] = $url;

        return view('qrcode_vente', $data);
    }

    // ARTICLES
    public function listeArticles()
    {
        $data = $this->init();
        if (!$data)
            return redirect()->to(base_url('Enchere/connexion'));
        $session = session();
        if (!in_array($session->get('role'), ['benevole', 'secretaire'])) {
            return redirect()->to('Enchere/index');
        }

        $monmodel = new \App\Models\Modele();
        $data['articles'] = $monmodel->getLesArticles();
        return view('liste_articles', $data);
    }

    public function creerArticle()
    {
        $data = $this->init();
        if (!$data)
            return redirect()->to(base_url('Enchere/connexion'));
        $session = session();
        if (!in_array($session->get('role'), ['benevole', 'secretaire'])) {
            return redirect()->to('Enchere/index');
        }
        return view('creer_article', $data);
    }

    public function validerCreerArticle()
    {
        $data = $this->init();
        if (!$data)
            return redirect()->to(base_url('Enchere/connexion'));
        $session = session();
        if (!in_array($session->get('role'), ['benevole', 'secretaire'])) {
            return redirect()->to('Enchere/index');
        }

        $rules = [
            'libelle' => 'required|max_length[255]',
            'etat' => 'required|in_list[bon,très bon,comme neuf]',
            'prix_origine' => 'required|numeric',
        ];

        if (!$this->validate($rules)) {
            return view('creer_article', $data);
        }

        $monmodel = new \App\Models\Modele();

        $articleData = [
            'libelle' => $this->request->getVar('libelle'),
            'description' => $this->request->getVar('description'),
            'taille' => $this->request->getVar('taille'),
            'etat' => $this->request->getVar('etat'),
            'prix_origine' => $this->request->getVar('prix_origine'),
        ];

        // Gestion photo
        $photo = $this->request->getFile('photo');
        if ($photo && $photo->isValid() && !$photo->hasMoved()) {
            $newName = $photo->getRandomName();
            $photo->move(FCPATH . 'uploads/articles/', $newName);
            $articleData['photo'] = 'uploads/articles/' . $newName;
        }

        $monmodel->insertArticle($articleData);
        return redirect()->to('Enchere/listeArticles');
    }

    public function supprimerArticle($idArticle)
    {
        $data = $this->init();
        if (!$data)
            return redirect()->to(base_url('Enchere/connexion'));
        $session = session();
        if (!in_array($session->get('role'), ['benevole', 'secretaire'])) {
            return redirect()->to('Enchere/index');
        }

        $monmodel = new \App\Models\Modele();
        // Vérifier si l'article est associé à une vente
        $estDansVente = $monmodel->db->table('vente_articles')->where('id_article', $idArticle)->countAllResults() > 0;

        if ($estDansVente) {
            session()->setFlashdata('erreur', 'Impossible de supprimer cet article : il est déjà associé à une ou plusieurs ventes.');
        } else {
            $monmodel->supprimerArticle($idArticle);
            session()->setFlashdata('succes', 'L\'article a été supprimé avec succès.');
        }

        return redirect()->to('Enchere/listeArticles');
    }

    public function selectionnerArticle($idVente)
    {
        $data = $this->init();
        if (!$data)
            return redirect()->to(base_url('Enchere/connexion'));

        $monmodel = new \App\Models\Modele();
        $session = session();
        $vente = $monmodel->getVenteParId($idVente);

        if (!$vente || $vente->etat !== 'a_venir') {
            return redirect()->to('Enchere/detailVente/' . $idVente);
        }

        $idArticle = $this->request->getVar('id_article');
        $prixDepart = $this->request->getVar('prix_depart');

        if ($prixDepart < 0.20) {
            return redirect()->to('Enchere/detailVente/' . $idVente);
        }

        if (!$monmodel->venteArticleExiste($idVente, $idArticle)) {
            $monmodel->insertVenteArticle([
                'id_vente' => $idVente,
                'id_article' => $idArticle,
                'id_benevole' => $session->get('id_utilisateur'),
                'prix_depart' => $prixDepart,
            ]);
        }
        return redirect()->to('Enchere/detailVente/' . $idVente);
    }

    public function retirerArticleVente($idVenteArticle)
    {
        $data = $this->init();
        if (!$data)
            return redirect()->to(base_url('Enchere/connexion'));

        $session = session();
        if (!in_array($session->get('role'), ['benevole', 'secretaire'])) {
            return redirect()->to('Enchere/index');
        }

        $monmodel = new \App\Models\Modele();
        $venteArticle = $monmodel->getVenteArticleDetail($idVenteArticle);

        if ($venteArticle) {
            // On ne peut retirer un article que si aucune enchère n'a été placée
            $nbEncheres = $monmodel->db->table('encheres')
                ->where('id_vente_article', $idVenteArticle)
                ->where('est_annulee', 0)
                ->countAllResults();

            if ($nbEncheres == 0) {
                // S'il y a des enchères annulées, c'est bloquant côté foreign key
                $nbToutesEncheres = $monmodel->db->table('encheres')->where('id_vente_article', $idVenteArticle)->countAllResults();
                if($nbToutesEncheres == 0) {
                    $monmodel->retirerVenteArticle($idVenteArticle);
                    session()->setFlashdata('succes', 'L\'article a été retiré de la vente.');
                } else {
                    session()->setFlashdata('erreur', 'Impossible de retirer l\'article : des requêtes en base existent pour ce lot.');
                }
            } else {
                session()->setFlashdata('erreur', 'Impossible de retirer l\'article : des enchères actives existent sur ce lot.');
            }
            return redirect()->to('Enchere/detailVente/' . $venteArticle->id_vente);
        }

        return redirect()->to('Enchere/listeVentes');
    }

    // ENCHERES
    public function encherir($idVenteArticle)
    {
        $data = $this->init();
        if (!$data)
            return redirect()->to(base_url('Enchere/connexion'));

        $session = session();

        // Restriction bénévole : ne peut pas enchérir
        if ($session->get('role') !== 'habitant') {
            return redirect()->to('Enchere/index');
        }

        $monmodel = new \App\Models\Modele();

        $venteArticle = $monmodel->getVenteArticleDetail($idVenteArticle);
        if (!$venteArticle || $venteArticle->vente_etat !== 'en_cours') {
            return redirect()->to('Enchere/listeVentes');
        }

        if (!$monmodel->estInscrit($venteArticle->id_vente, $session->get('id_utilisateur'))) {
            return redirect()->to('Enchere/detailVente/' . $venteArticle->id_vente);
        }

        $montant = (float) $this->request->getVar('montant');
        $montantMax = $monmodel->getMontantMax($idVenteArticle);
        $minimum = max($venteArticle->prix_depart, 0.20);

        if ($montantMax > 0) {
            $minimum = $montantMax + 0.10;
        }

        if ($montant < $minimum) {
            return redirect()->to('Enchere/detailVente/' . $venteArticle->id_vente);
        }

        $monmodel->insertEnchere([
            'id_vente_article' => $idVenteArticle,
            'id_utilisateur' => $session->get('id_utilisateur'),
            'montant' => $montant,
            'est_annulee' => 0,
            'date_enchere' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('Enchere/detailVente/' . $venteArticle->id_vente);
    }

    public function annulerEnchere($idEnchere)
    {
        $data = $this->init();
        if (!$data)
            return redirect()->to(base_url('Enchere/connexion'));

        $monmodel = new \App\Models\Modele();
        $session = session();
        $enchere = $monmodel->getEnchereParId($idEnchere);

        if ($enchere && $enchere->id_utilisateur == $session->get('id_utilisateur')) {
            // Vérifier que la vente est encore en cours avant d'annuler
            $venteArticle = $monmodel->getVenteArticleDetail($enchere->id_vente_article);
            if ($venteArticle && $venteArticle->vente_etat === 'en_cours') {
                $monmodel->annulerEnchere($idEnchere);
            }
        }
        return redirect()->to('Enchere/historiqueEncheres');
    }

    public function historiqueEncheres()
    {
        $data = $this->init();
        if (!$data)
            return redirect()->to(base_url('Enchere/connexion'));

        $session = session();

        // Seuls les habitants peuvent consulter leur historique d'enchères
        if ($session->get('role') !== 'habitant') {
            return redirect()->to('Enchere/index');
        }

        $monmodel = new \App\Models\Modele();
        $data['encheres'] = $monmodel->getHistoriqueEncheres($session->get('id_utilisateur'));
        return view('historique_encheres', $data);
    }

    // ACHATS
    public function mesAchats()
    {
        $data = $this->init();
        if (!$data)
            return redirect()->to(base_url('Enchere/connexion'));

        $monmodel = new \App\Models\Modele();
        $session = session();
        $data['achats'] = $monmodel->getAchatsUtilisateur($session->get('id_utilisateur'));
        return view('mes_achats', $data);
    }

    public function confirmerAchat($idAchat)
    {
        $data = $this->init();
        if (!$data)
            return redirect()->to(base_url('Enchere/connexion'));

        $monmodel = new \App\Models\Modele();
        $session = session();
        $achat = $monmodel->getAchatParId($idAchat);

        if ($achat && $achat->id_utilisateur == $session->get('id_utilisateur')) {
            $monmodel->confirmerAchat($idAchat);
        }
        return redirect()->to('Enchere/mesAchats');
    }

    public function recuAchat($idAchat)
    {
        $data = $this->init();
        if (!$data)
            return redirect()->to(base_url('Enchere/connexion'));

        $monmodel = new \App\Models\Modele();
        $session = session();

        $achat = $monmodel->getAchatDetail($idAchat);
        if (!$achat) {
            return redirect()->to('Enchere/mesAchats');
        }

        // Seul l'acheteur ou le secrétaire peut voir le reçu
        if ($achat->id_utilisateur != $session->get('id_utilisateur') && $session->get('role') !== 'secretaire') {
            return redirect()->to('Enchere/mesAchats');
        }

        // Construire l'objet acheteur pour la vue
        $acheteur = (object) [
            'nom' => $achat->acheteur_nom,
            'prenom' => $achat->acheteur_prenom,
            'email' => $achat->acheteur_email,
            'adresse' => $achat->acheteur_adresse,
            'telephone' => $achat->acheteur_telephone,
        ];

        $data['achat'] = $achat;
        $data['acheteur'] = $acheteur;

        return view('recu_achat', $data);
    }

    // PROFIL
    public function profil()
    {
        $data = $this->init();
        if (!$data)
            return redirect()->to(base_url('Enchere/connexion'));

        $monmodel = new \App\Models\Modele();
        $session = session();
        $data['utilisateur'] = $monmodel->getUtilisateurParId($session->get('id_utilisateur'));
        return view('profil', $data);
    }

    public function modifierProfil()
    {
        $data = $this->init();
        if (!$data)
            return redirect()->to(base_url('Enchere/connexion'));

        $session = session();
        $idUtilisateur = $session->get('id_utilisateur');
        $monmodel = new \App\Models\Modele();
        $utilisateur = $monmodel->getUtilisateurParId($idUtilisateur);

        $updateData = [
            'nom' => $this->request->getPost('nom'),
            'prenom' => $this->request->getPost('prenom'),
            'telephone' => $this->request->getPost('telephone'),
            'adresse' => $this->request->getPost('adresse'),
            'ville' => $this->request->getPost('ville'),
            'code_postal' => $this->request->getPost('code_postal'),
        ];

        if (
            (int) $utilisateur->est_habitant === 1
            && !$this->estAdresseHabitantValide($updateData['ville'], $updateData['code_postal'])
        ) {
            $data['utilisateur'] = $utilisateur;
            $data['erreur_mdp'] = 'Les habitants doivent conserver une adresse a Getcet (99999).';
            return view('profil', $data);
        }

        $newMdp = $this->request->getPost('nouveau_mot_de_passe');
        if (!empty($newMdp)) {
            // Validation mot de passe fort
            if (strlen($newMdp) < 8 || !preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $newMdp)) {
                $data['utilisateur'] = $monmodel->getUtilisateurParId($idUtilisateur);
                $data['erreur_mdp'] = 'Le mot de passe doit contenir au moins 8 caractères, 1 majuscule, 1 minuscule, 1 chiffre et 1 caractère spécial.';
                return view('profil', $data);
            }
            $updateData['mot_de_passe'] = password_hash($newMdp, PASSWORD_DEFAULT);
        }

        // Utilisation du modèle standard pour la mise à jour
        $utilisateurModel = new \App\Models\UtilisateurModel();

        // On ignore la validation du modèle ici car les règles 'required' (email, etc.) 
        // bloquent les mises à jour partielles si les champs ne sont pas fournis.
        if ($utilisateurModel->skipValidation(true)->update($idUtilisateur, $updateData)) {
            // Mise à jour de la session uniquement en cas de succès
            $session->set('nom', $updateData['nom']);
            $session->set('prenom', $updateData['prenom']);
            $session->setFlashdata('success', 'Votre profil a été mis à jour avec succès.');
        } else {
            $session->setFlashdata('error', 'Une erreur est survenue lors de la mise à jour de votre profil.');
        }

        return redirect()->to('Enchere/profil');
    }

    // DASHBOARD (secrétaire)
    public function dashboard()
    {
        $data = $this->init();
        if (!$data)
            return redirect()->to(base_url('Enchere/connexion'));
        $session = session();
        if ($session->get('role') !== 'secretaire') {
            return redirect()->to('Enchere/index');
        }

        $monmodel = new \App\Models\Modele();
        $monmodel->mettreAJourStatutsVentes();

        $ventesParEtat = $monmodel->compterVentesParEtat();
        $statsVentes = ['a_venir' => 0, 'en_cours' => 0, 'cloturee' => 0];
        foreach ($ventesParEtat as $v) {
            $statsVentes[$v->etat] = $v->total;
        }

        $data['statsVentes'] = $statsVentes;
        $data['totalVentes'] = array_sum($statsVentes);
        $data['montantTotal'] = $monmodel->getMontantTotalAchats();
        $data['nbUtilisateurs'] = $monmodel->getNbUtilisateurs();
        $data['nbArticles'] = $monmodel->getNbArticles();
        $data['dernieresVentes'] = $monmodel->getLesVentes();

        // Variables manquantes pour les graphiques et stats avancées
        $data['evolutionEncheres'] = $monmodel->getEvolutionEncheres(7);
        $data['topArticles'] = $monmodel->getArticlesPlusEncheris(5);
        $data['tauxParticipation'] = $monmodel->getTauxParticipation();

        return view('dashboard', $data);
    }
}

