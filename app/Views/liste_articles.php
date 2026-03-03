<html>

<head>
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

        h1 {
            color: #2c3e50;
            margin: 20px;
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
        }

        .btn-success {
            background-color: #27ae60;
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
            max-width: 350px;
        }

        .badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
        }

        .badge-info {
            background-color: #d1ecf1;
            color: #0c5460;
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

        footer {
            background-color: #2c3e50;
            color: white;
            text-align: center;
            padding: 15px;
            margin-top: 30px;
        }
    </style>
</head>

<body>
    <?= view('nav'); ?>

    <div class="container">
        <h1>Articles</h1>
        <?= anchor('Enchere/creerArticle', '+ Ajouter un article', ['class' => 'btn btn-success']); ?>

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
                            <?= $article->description ?? 'Aucune description'; ?>
                        </p>
                        <p class="prix">
                            <?= number_format($article->prix_origine, 2); ?> €
                        </p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p style="text-align: center; padding: 30px; color: #7f8c8d;">Aucun article. Ajoutez-en un !</p>
        <?php endif; ?>
    </div>

    <footer>
        <p>&copy;
            <?= date('Y'); ?> EnchèreAPorter — Ville de Getcet
        </p>
    </footer>
</body>

</html>