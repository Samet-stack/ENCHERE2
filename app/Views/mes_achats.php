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
            font-size: 13px;
        }

        .btn-success {
            background-color: #27ae60;
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
        <h1>Mes achats</h1>

        <?php if (!empty($achats)): ?>
            <div class="card-grid">
                <?php foreach ($achats as $achat): ?>
                    <div class="card card-item">
                        <?php if (!empty($achat->photo)): ?>
                            <img src="<?= base_url($achat->photo); ?>" alt="<?= $achat->article_libelle; ?>" class="article-photo">
                        <?php
        endif; ?>
                        <h3>
                            <?= $achat->article_libelle; ?>
                        </h3>
                        <?php if ($achat->confirme): ?>
                            <span class="badge badge-success">Confirmé</span>
                        <?php
        else: ?>
                            <span class="badge badge-warning">En attente</span>
                        <?php
        endif; ?>
                        <p>Vente :
                            <?= $achat->vente_titre; ?>
                        </p>
                        <p class="prix">Montant :
                            <?= number_format($achat->montant_final, 2); ?> €
                        </p>

                        <?php if (!$achat->confirme): ?>
                            <?= anchor('Enchere/confirmerAchat/' . $achat->id_achat, "Confirmer l'achat", ['class' => 'btn btn-success']); ?>
                        <?php
        else: ?>
                            <p style="color: #27ae60; font-size: 13px;">Confirmé le
                                <?= date('d/m/Y à H:i', strtotime($achat->date_confirmation)); ?>
                            </p>
                        <?php
        endif; ?>
                        <?= anchor('Enchere/recuAchat/' . $achat->id_achat, '📄 Reçu', ['class' => 'btn', 'target' => '_blank', 'style' => 'margin-top: 8px;']); ?>
                    </div>
                <?php
    endforeach; ?>
            </div>
        <?php
else: ?>
            <p style="text-align: center; padding: 30px; color: #7f8c8d;">Aucun achat.
                <?= anchor('Enchere/listeVentes', 'Voir les ventes'); ?>
            </p>
        <?php
endif; ?>
    </div>

    <footer>
        <p>&copy;
            <?= date('Y'); ?> EnchèreAPorter — Ville de Getcet
        </p>
    </footer>
</body>

</html>