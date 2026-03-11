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
            font-weight: bold;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin: 15px 0;
            background-color: white;
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
            margin: 10px 0 30px;
        }

        @media (max-width: 768px) {
            .stats-grid { flex-direction: column; }
            .stat-card { min-width: auto; }
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
        }
    </style>
</head>

<body>
    <?= view('nav'); ?>

    <div class="container">
        <h1>Tableau de bord</h1>

        <!-- Statistiques Simples -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-value">
                    <?= $totalVentes; ?>
                </div>
                <div class="stat-label">Ventes totales</div>
            </div>
            <div class="stat-card">
                <div class="stat-value" style="color: #27ae60;">
                    <?= $statsVentes['en_cours']; ?>
                </div>
                <div class="stat-label">Ventes en cours</div>
            </div>
            <div class="stat-card">
                <div class="stat-value" style="color: #e67e22;">
                    <?= number_format($montantTotal, 2); ?> €
                </div>
                <div class="stat-label">Revenus confirmés</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">
                    <?= $nbUtilisateurs; ?>
                </div>
                <div class="stat-label">Utilisateurs inscrits</div>
            </div>
            <div class="stat-card">
                <div class="stat-value" style="color: #8e44ad;">
                    <?= $nbArticles; ?>
                </div>
                <div class="stat-label">Articles mis aux enchères</div>
            </div>
            <div class="stat-card">
                <div class="stat-value" style="color: #f1c40f;">
                    <?= $statsVentes['a_venir']; ?>
                </div>
                <div class="stat-label">Ventes à venir</div>
            </div>
        </div>

        <!-- Actions rapides -->
        <div class="card">
            <h3 style="margin-top: 0; color: #2c3e50;">Actions rapides</h3>
            <?= anchor('Enchere/creerVente', '+ Nouvelle vente', ['class' => 'btn btn-success']); ?>
            <?= anchor('Enchere/creerArticle', '+ Nouvel article', ['class' => 'btn btn-success']); ?>
            <?= anchor('Enchere/listeVentes', 'Gérer les ventes', ['class' => 'btn']); ?>
            <?= anchor('Enchere/listeArticles', 'Gérer les articles', ['class' => 'btn']); ?>
        </div>

        <!-- Dernières ventes -->
        <h2>Dernières ventes</h2>
        <?php if (!empty($dernieresVentes)): ?>
            <table>
                <tr>
                    <th>Titre de la vente</th>
                    <th>État</th>
                    <th>Date de début</th>
                    <th>Date de fin</th>
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
                            <?= anchor('Enchere/qrcodeVente/' . $vente->id_vente, 'QR Code', ['class' => 'btn']); ?>
                            <?php if ($vente->etat !== 'cloturee'): ?>
                                <?= anchor('Enchere/cloturerVente/' . $vente->id_vente, 'Clôturer la vente', ['class' => 'btn btn-danger', 'onclick' => "return confirm('Êtes-vous sûr de vouloir clôturer cette vente ?')"]); ?>
                            <?php
        endif; ?>
                        </td>
                    </tr>
                <?php
    endforeach; ?>
            </table>
        <?php
else: ?>
            <div class="card">
                <p style="color: #7f8c8d; text-align: center; margin: 0;">Aucune vente enregistrée pour le moment. Vous pouvez en créer une depuis les actions rapides !</p>
            </div>
        <?php
endif; ?>
    </div>

    <!-- PRIX D'OR : Articles les plus populaires -->
    <div class="container" style="margin-top: -10px;">
        <h2>🏆 Articles les plus populaires</h2>
        <?php if (!empty($topArticles)): ?>
            <table>
                <tr>
                    <th>Article</th>
                    <th>Total d'enchères reçues</th>
                </tr>
                <?php foreach ($topArticles as $article): ?>
                    <tr>
                        <td style="font-size: 16px;"><strong><?= $article->libelle; ?></strong></td>
                        <td><span class="badge badge-success" style="font-size: 14px; padding: 6px 12px;"><?= $article->nb_encheres; ?> enchères</span></td>
                    </tr>
                <?php
    endforeach; ?>
            </table>
        <?php
else: ?>
            <div class="card">
                <p style="color: #7f8c8d; text-align: center; margin: 0;">Aucune enchère n'a encore été effectuée sur vos articles.</p>
            </div>
        <?php
endif; ?>
    </div>

    <!-- Évolution des enchères (7 derniers jours) -->
    <div class="container" style="margin-top: -10px;">
        <h2>📈 Évolution des enchères (7 derniers jours)</h2>
        <?php if (!empty($evolutionEncheres)): ?>
            <table>
                <tr>
                    <th>Date</th>
                    <th>Nombre d'enchères</th>
                </tr>
                <?php foreach ($evolutionEncheres as $jour): ?>
                    <tr>
                        <td><?= date('d/m/Y', strtotime($jour->jour)); ?></td>
                        <td><span class="badge badge-success" style="font-size: 14px; padding: 6px 12px;"><?= $jour->total; ?></span></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <div class="card">
                <p style="color: #7f8c8d; text-align: center; margin: 0;">Aucune enchère sur les 7 derniers jours.</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Taux de participation par vente -->
    <div class="container" style="margin-top: -10px;">
        <h2>📊 Taux de participation par vente</h2>
        <?php if (!empty($tauxParticipation)): ?>
            <table>
                <tr>
                    <th>Vente</th>
                    <th>Taux de participation</th>
                </tr>
                <?php foreach ($tauxParticipation as $vente): ?>
                    <tr>
                        <td><?= $vente->titre; ?></td>
                        <td>
                            <div style="background-color: #ecf0f1; border-radius: 10px; overflow: hidden; height: 24px; position: relative;">
                                <div style="background-color: <?= $vente->taux >= 50 ? '#27ae60' : ($vente->taux >= 25 ? '#f39c12' : '#e74c3c'); ?>; height: 100%; width: <?= $vente->taux; ?>%; border-radius: 10px; transition: width 0.3s;"></div>
                                <span style="position: absolute; top: 2px; left: 50%; transform: translateX(-50%); font-weight: bold; font-size: 13px;"><?= $vente->taux; ?>%</span>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <div class="card">
                <p style="color: #7f8c8d; text-align: center; margin: 0;">Aucune donnée de participation disponible.</p>
            </div>
        <?php endif; ?>
    </div>

    <footer>
        <p>&copy;
            <?= date('Y'); ?> EnchèreAPorter — Ville de Getcet
        </p>
    </footer>

</body>

</html>