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

        .btn-success {
            background-color: #27ae60;
        }

        .btn-danger {
            background-color: #e74c3c;
        }

        .stats-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin: 15px 0;
        }

        .stat-card {
            flex: 1;
            min-width: 180px;
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
        }

        .stat-value {
            font-size: 32px;
            font-weight: bold;
            color: #3498db;
        }

        .stat-label {
            color: #7f8c8d;
            margin-top: 5px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin: 15px 0;
        }

        table th,
        table td {
            border: 1px solid #bdc3c7;
            padding: 10px;
            text-align: left;
        }

        table th {
            background-color: #2c3e50;
            color: white;
        }

        table tr:nth-child(even) {
            background-color: #ecf0f1;
        }

        table tr:hover {
            background-color: #d5dbdb;
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
        <h1>Tableau de bord</h1>

        <!-- Statistiques -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-value">
                    <?= $totalVentes; ?>
                </div>
                <div class="stat-label">Ventes totales</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">
                    <?= $statsVentes['en_cours']; ?>
                </div>
                <div class="stat-label">Ventes en cours</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">
                    <?= number_format($montantTotal, 2); ?> €
                </div>
                <div class="stat-label">Revenus confirmés</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">
                    <?= $nbUtilisateurs; ?>
                </div>
                <div class="stat-label">Utilisateurs</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">
                    <?= $nbArticles; ?>
                </div>
                <div class="stat-label">Articles</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">
                    <?= $statsVentes['a_venir']; ?>
                </div>
                <div class="stat-label">Ventes à venir</div>
            </div>
        </div>

        <!-- Actions rapides -->
        <div class="card">
            <h3>Actions rapides</h3>
            <?= anchor('Enchere/creerVente', '+ Nouvelle vente', ['class' => 'btn btn-success']); ?>
            <?= anchor('Enchere/creerArticle', '+ Nouvel article', ['class' => 'btn btn-success']); ?>
            <?= anchor('Enchere/listeVentes', 'Voir les ventes', ['class' => 'btn']); ?>
            <?= anchor('Enchere/listeArticles', 'Voir les articles', ['class' => 'btn']); ?>
        </div>

        <!-- Dernières ventes -->
        <h2>Dernières ventes</h2>
        <?php if (!empty($dernieresVentes)): ?>
            <table>
                <tr>
                    <th>Titre</th>
                    <th>État</th>
                    <th>Début</th>
                    <th>Fin</th>
                    <th>Actions</th>
                </tr>
                <?php foreach ($dernieresVentes as $vente): ?>
                    <tr>
                        <td><strong>
                                <?= $vente->titre; ?>
                            </strong></td>
                        <td>
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
                        </td>
                        <td>
                            <?= date('d/m/Y H:i', strtotime($vente->date_debut)); ?>
                        </td>
                        <td>
                            <?= date('d/m/Y H:i', strtotime($vente->date_fin)); ?>
                        </td>
                        <td>
                            <?= anchor('Enchere/detailVente/' . $vente->id_vente, 'Détails', ['class' => 'btn']); ?>
                            <?= anchor('Enchere/qrcodeVente/' . $vente->id_vente, 'QR', ['class' => 'btn']); ?>
                            <?php if ($vente->etat !== 'cloturee'): ?>
                                <?= anchor('Enchere/cloturerVente/' . $vente->id_vente, 'Clôturer', ['class' => 'btn btn-danger', 'onclick' => "return confirm('Clôturer ?')"]); ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p style="color: #7f8c8d;">Aucune vente. Créez-en une !</p>
        <?php endif; ?>
    </div>

    <footer>
        <p>&copy;
            <?= date('Y'); ?> EnchèreAPorter — Ville de Getcet
        </p>
    </footer>
</body>

</html>