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
        h2 {
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

        .btn:hover {
            background-color: #2980b9;
        }

        .btn-success {
            background-color: #27ae60;
        }

        .btn-danger {
            background-color: #e74c3c;
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
            min-width: 300px;
            max-width: 400px;
        }

        .filter-bar {
            margin: 15px 0;
        }

        .filter-bar a {
            padding: 8px 16px;
            margin: 3px;
            text-decoration: none;
            border-radius: 20px;
            border: 1px solid #bdc3c7;
            color: #2c3e50;
            display: inline-block;
        }

        .filter-bar a.active {
            background-color: #3498db;
            color: white;
            border-color: #3498db;
        }

        .filter-bar a:hover {
            background-color: #3498db;
            color: white;
        }

        @media (max-width: 768px) {
            .card-grid { flex-direction: column; }
            .card-item { max-width: 100%; min-width: auto; }
            .container { padding: 10px; }
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
        <h1>Ventes aux enchères</h1>

        <?php if (session()->get('role') === 'secretaire'): ?>
            <?= anchor('Enchere/creerVente', '+ Nouvelle vente', ['class' => 'btn btn-success']); ?>
        <?php
endif; ?>

        <!-- Filtres -->
        <?php if (session()->get('role') !== 'benevole'): ?>
            <div class="filter-bar">
                <?= anchor('Enchere/listeVentes', 'Toutes', ['class' => 'btn' . (empty($filtre) ? ' active' : '')]); ?>
                <?= anchor('Enchere/listeVentes?etat=en_cours', 'En cours', ['class' => 'btn' . ($filtre === 'en_cours' ? ' active' : '')]); ?>
                <?= anchor('Enchere/listeVentes?etat=a_venir', 'À venir', ['class' => 'btn' . ($filtre === 'a_venir' ? ' active' : '')]); ?>
                <?= anchor('Enchere/listeVentes?etat=cloturee', 'Clôturées', ['class' => 'btn' . ($filtre === 'cloturee' ? ' active' : '')]); ?>
            </div>
        <?php
else: ?>
            <div style="background-color: #d1ecf1; color: #0c5460; padding: 15px; border-radius: 4px; margin: 15px 0;">
                ℹ️ En tant que bénévole, vous n'avez accès qu'aux ventes clôturées.
            </div>
        <?php
endif; ?>

        <?php if (!empty($ventes)): ?>
            <div class="card-grid">
                <?php foreach ($ventes as $vente): ?>
                    <div class="card card-item">
                        <h3>
                            <?= $vente->titre; ?>
                        </h3>
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
                        <p>
                            <?= $vente->description ?? 'Aucune description'; ?>
                        </p>
                        <p><strong>Du</strong>
                            <?= date('d/m/Y H:i', strtotime($vente->date_debut)); ?>
                            <strong>au</strong>
                            <?= date('d/m/Y H:i', strtotime($vente->date_fin)); ?>
                        </p>
                        <?= anchor('Enchere/detailVente/' . $vente->id_vente, 'Voir détails →', ['class' => 'btn']); ?>
                    </div>
                <?php
    endforeach; ?>
            </div>
        <?php
else: ?>
            <p style="text-align: center; padding: 40px; color: #7f8c8d;">Aucune vente trouvée pour ce filtre.</p>
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