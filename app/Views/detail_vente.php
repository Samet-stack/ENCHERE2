<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= $titre; ?>
    </title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        nav {
            background-color: #2c3e50;
            overflow: hidden;
            padding: 10px 20px;
        }

        nav a {
            float: left;
            color: white;
            text-align: center;
            padding: 12px 16px;
            text-decoration: none;
            font-size: 15px;
            border-radius: 4px;
        }

        nav a:hover {
            background-color: #34495e;
        }

        .nav-right {
            float: right;
        }

        h1,
        h2,
        h3 {
            color: #2c3e50;
        }

        .container {
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .btn {
            display: inline-block;
            padding: 8px 16px;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin: 3px;
            border: none;
            cursor: pointer;
            font-size: 13px;
        }

        .btn:hover {
            background-color: #2980b9;
        }

        .btn-success {
            background-color: #27ae60;
        }

        .btn-success:hover {
            background-color: #219a52;
        }

        .btn-danger {
            background-color: #e74c3c;
        }

        .btn-danger:hover {
            background-color: #c0392b;
        }

        .badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
        }

        .badge-success {
            background-color: #d4edda;
            color: #155724;
        }

        .badge-warning {
            background-color: #fff3cd;
            color: #856404;
        }

        .badge-danger {
            background-color: #f8d7da;
            color: #721c24;
        }

        .badge-info {
            background-color: #d1ecf1;
            color: #0c5460;
        }

        .card {
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin: 10px 0;
        }

        .card-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }

        .card-item {
            flex: 1;
            min-width: 280px;
            max-width: 380px;
        }

        .prix {
            color: #27ae60;
            font-weight: bold;
            font-size: 16px;
        }

        .article-photo {
            max-width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 4px;
            margin-bottom: 10px;
        }

        .sidebar {
            float: right;
            width: 300px;
            margin-left: 20px;
        }

        .main-content {
            overflow: hidden;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin: 10px 0;
        }

        table th,
        table td {
            border: 1px solid #bdc3c7;
            padding: 8px;
            text-align: left;
        }

        table th {
            background-color: #2c3e50;
            color: white;
        }

        input[type=text],
        input[type=number],
        select {
            width: 100%;
            padding: 8px;
            margin: 5px 0 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        label {
            font-weight: bold;
            color: #2c3e50;
        }

        .countdown {
            background-color: #ffeaa7;
            padding: 8px 15px;
            border-radius: 4px;
            display: inline-block;
            font-weight: bold;
            margin: 10px 0;
        }

        @media (max-width: 768px) {
            .sidebar { float: none; width: 100%; margin-left: 0; margin-bottom: 15px; }
            .card-grid { flex-direction: column; }
            .card-item { max-width: 100%; min-width: auto; }
            table { font-size: 13px; }
            table th, table td { padding: 6px; }
            .container { padding: 10px; }
        }

        footer {
            background-color: #2c3e50;
            color: white;
            text-align: center;
            padding: 15px;
            margin-top: 30px;
            clear: both;
        }
    </style>
</head>

<body>
    <?= view('nav'); ?>

    <div class="container">
        <h1>
            <?= $vente->titre; ?>
        </h1>
        <p>Organisée par
            <?= $vente->secretaire_prenom . ' ' . $vente->secretaire_nom; ?>
        </p>

        <?php
        $badgeClass = 'badge-warning';
        $badgeLabel = $vente->etat;
        if ($vente->etat === 'en_cours') {
            $badgeClass = 'badge-success';
            $badgeLabel = 'En cours';
        }
        if ($vente->etat === 'a_venir') {
            $badgeClass = 'badge-warning';
            $badgeLabel = 'À venir';
        }
        if ($vente->etat === 'cloturee') {
            $badgeClass = 'badge-danger';
            $badgeLabel = 'Clôturée';
        }
        ?>
        <span class="badge <?= $badgeClass; ?>">
            <?= $badgeLabel; ?>
        </span>

        <!-- Actions secrétaire -->
        <?php if (session()->get('role') === 'secretaire'): ?>
            <?= anchor('Enchere/qrcodeVente/' . $vente->id_vente, 'QR Code', ['class' => 'btn']); ?>
            <?php if ($vente->etat !== 'cloturee'): ?>
                <?= anchor('Enchere/cloturerVente/' . $vente->id_vente, 'Clôturer la vente', ['class' => 'btn btn-danger', 'onclick' => "return confirm('Êtes-vous sûr ?')"]); ?>
            <?php endif; ?>
        <?php endif; ?>

        <!-- Sidebar -->
        <div class="sidebar">
            <div class="card">
                <h3>Informations</h3>
                <p><strong>Début :</strong>
                    <?= date('d/m/Y à H:i', strtotime($vente->date_debut)); ?>
                </p>
                <p><strong>Fin :</strong>
                    <?= date('d/m/Y à H:i', strtotime($vente->date_fin)); ?>
                </p>
                <p><strong>Articles :</strong>
                    <?= count($articles); ?>
                </p>

            </div>

            <!-- Inscription -->
            <?php if (session()->get('id_utilisateur') && session()->get('role') === 'habitant' && $vente->etat === 'a_venir'): ?>
                <div class="card">
                    <h3>Inscription</h3>
                    <?php if ($estInscrit): ?>
                        <span class="badge badge-success">Vous êtes inscrit</span>
                    <?php else: ?>
                        <?= anchor('Enchere/inscrireVente/' . $vente->id_vente, "S'inscrire à cette vente", ['class' => 'btn btn-success']); ?>
                    <?php endif; ?>
                </div>
            <?php elseif (!session()->get('id_utilisateur') && $vente->etat === 'a_venir'): ?>
                <div class="card">
                    <h3>Inscription</h3>
                    <p style="color: #7f8c8d;">Connectez-vous pour vous inscrire à cette vente.</p>
                    <?= anchor('Enchere/connexion', 'Se connecter', ['class' => 'btn btn-success']); ?>
                </div>
            <?php endif; ?>

            <!-- Inscrits (secrétaire uniquement) -->
            <?php if (session()->get('role') === 'secretaire' && !empty($inscrits)): ?>
                <div class="card">
                    <h3>Inscrits (
                        <?= count($inscrits); ?>)
                    </h3>
                    <?php foreach ($inscrits as $inscrit): ?>
                        <p>
                            <?= $inscrit->prenom . ' ' . $inscrit->nom; ?>
                        </p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Contenu principal -->
        <div class="main-content">
            <?php if (!empty($vente->description)): ?>
                <div class="card">
                    <h3>Description</h3>
                    <p>
                        <?= nl2br($vente->description); ?>
                    </p>
                </div>
            <?php endif; ?>

            <h2>Articles en vente</h2>

            <?php if (!empty($articles)): ?>
                <div class="card-grid">
                    <?php foreach ($articles as $article): ?>
                        <div class="card card-item">
                            <?php if (!empty($article->photo)): ?>
                                <img src="<?= base_url($article->photo); ?>" alt="<?= $article->libelle; ?>" class="article-photo">
                            <?php endif; ?>

                            <h3>
                                <?= $article->libelle; ?>
                            </h3>
                            <span class="badge badge-info">
                                <?= $article->taille ?? 'N/A'; ?>
                            </span>
                            <span class="badge badge-info">
                                <?= $article->etat; ?>
                            </span>
                            <p>
                                <?= $article->description ?? ''; ?>
                            </p>
                            <p>Prix origine :
                                <?= number_format($article->prix_origine, 2); ?> €
                            </p>

                            <?php
                            // Restriction bénévole : masquer les montants d'enchères si vente non clôturée
                            $estBenevole = (session()->get('role') === 'benevole');
                            $venteTerminee = ($vente->etat === 'cloturee');
                            ?>

                            <?php if (!$estBenevole || $venteTerminee): ?>
                                <p class="prix">
                                    <?php if ($article->enchere_max): ?>
                                        Enchère max :
                                        <?= number_format($article->enchere_max, 2); ?> €
                                    <?php else: ?>
                                        Prix départ :
                                        <?= number_format($article->prix_depart, 2); ?> €
                                    <?php endif; ?>
                                </p>
                                <p>
                                    <?= $article->nb_encheres; ?> enchère(s)
                                </p>
                            <?php else: ?>
                                <p style="color: #7f8c8d; font-style: italic;">🔒 Enchères visibles après clôture de la vente.</p>
                            <?php endif; ?>

                            <!-- Formulaire d'enchère (masqué pour les bénévoles et secrétaires) -->
                            <?php if ($vente->etat === 'en_cours' && session()->get('id_utilisateur') && !$estBenevole && session()->get('role') !== 'secretaire' && $estInscrit): ?>
                                <?php
                                $minimum = max($article->prix_depart, 0.20);
                                if ($article->enchere_max) {
                                    $minimum = $article->enchere_max + 0.10;
                                }
                                ?>
                                <?= form_open('Enchere/encherir/' . $article->id_vente_article) ?>
                                <?= form_label('Enchérir (min. ' . number_format($minimum, 2) . ' €) : '); ?>
                                <?php echo form_input(['name' => 'montant', 'type' => 'number', 'step' => '0.10', 'min' => $minimum, 'value' => $minimum]); ?>
                                <?php echo form_submit('encherir', 'Enchérir', ['class' => 'btn btn-success']); ?>
                                <?= form_close(); ?>
                            <?php elseif ($vente->etat === 'en_cours' && session()->get('role') === 'habitant' && !$estInscrit): ?>
                                <p style="color: #7f8c8d; font-style: italic;">Participation reservee aux habitants inscrits avant l'ouverture de la vente.</p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p style="text-align: center; padding: 30px; color: #7f8c8d;">Aucun article dans cette vente.</p>
            <?php endif; ?>

            <!-- Sélectionner un article (bénévole/secrétaire) -->
            <?php if (in_array(session()->get('role'), ['benevole', 'secretaire']) && $vente->etat !== 'cloturee'): ?>
                <div class="card" style="margin-top: 20px;">
                    <h3>Ajouter un article à cette vente</h3>
                    <?= form_open('Enchere/selectionnerArticle/' . $vente->id_vente) ?>
                    <?= form_label('Article : '); ?>
                    <select name="id_article">
                        <option value="">-- Sélectionner un article --</option>
                        <?php foreach ($articlesDisponibles as $a): ?>
                            <option value="<?= $a->id_article; ?>">
                                <?= $a->libelle; ?> (
                                <?= $a->taille ?? 'N/A'; ?>) -
                                <?= number_format($a->prix_origine, 2); ?> €
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?= form_label('Prix de départ (min. 0.20 €) : '); ?>
                    <?php echo form_input(['name' => 'prix_depart', 'type' => 'number', 'step' => '0.10', 'min' => '0.20', 'value' => '0.20']); ?>
                    <?php echo form_submit('ajouter', "Ajouter l'article", ['class' => 'btn btn-success']); ?>
                    <?= form_close(); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <footer>
        <p>&copy;
            <?= date('Y'); ?> EnchèreAPorter — Ville de Getcet
        </p>
    </footer>
</body>

</html>

