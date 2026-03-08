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

        .charts-container {
            display: flex;
            gap: 20px;
            margin: 20px 0;
        }

        .chart-box {
            flex: 1;
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

        <!-- Graphiques Statistiques -->
        <h2>Évolution et Répartition</h2>
        <div class="charts-container">
            <div class="chart-box">
                <canvas id="ventesChart"></canvas>
            </div>
            <div class="chart-box">
                <canvas id="caChart"></canvas>
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
                            <?php
        endif; ?>
                        </td>
                    </tr>
                <?php
    endforeach; ?>
            </table>
        <?php
else: ?>
            <p style="color: #7f8c8d;">Aucune vente. Créez-en une !</p>
        <?php
endif; ?>
    </div>

    <!-- STATISTIQUES AVANCÉES : Articles les plus enchéris -->
    <div class="container">
        <h2>🏆 Articles les plus enchéris</h2>
        <?php if (!empty($topArticles)): ?>
            <table>
                <tr>
                    <th>Article</th>
                    <th>Nombre d'enchères</th>
                </tr>
                <?php foreach ($topArticles as $article): ?>
                    <tr>
                        <td><?= $article->libelle; ?></td>
                        <td><strong><?= $article->nb_encheres; ?></strong></td>
                    </tr>
                <?php
    endforeach; ?>
            </table>
        <?php
else: ?>
            <p style="color: #7f8c8d;">Aucune enchère pour le moment.</p>
        <?php
endif; ?>

        <!-- STATISTIQUES AVANCÉES : Évolution des enchères -->
        <div class="charts-container">
            <div class="chart-box">
                <canvas id="evolutionChart"></canvas>
            </div>
        </div>

        <!-- STATISTIQUES AVANCÉES : Taux de participation par vente -->
        <h2>📈 Taux de participation par vente</h2>
        <?php if (!empty($tauxParticipation)): ?>
            <table>
                <tr>
                    <th>Vente</th>
                    <th>Taux de participation</th>
                </tr>
                <?php foreach ($tauxParticipation as $v): ?>
                    <tr>
                        <td><?= $v->titre; ?></td>
                        <td>
                            <div style="background: #ecf0f1; border-radius: 10px; overflow: hidden;">
                                <div style="background: <?= $v->taux >= 50 ? '#27ae60' : '#f39c12'; ?>; width: <?= $v->taux; ?>%; padding: 4px 8px; color: white; font-size: 12px; min-width: 30px; text-align: center;">
                                    <?= $v->taux; ?>%
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php
    endforeach; ?>
            </table>
        <?php
else: ?>
            <p style="color: #7f8c8d;">Aucune donnée de participation.</p>
        <?php
endif; ?>
    </div>

    <footer>
        <p>&copy;
            <?= date('Y'); ?> EnchèreAPorter — Ville de Getcet
        </p>
    </footer>

    <script>
        // Graphique des Ventes (Camembert)
        const ctxVentes = document.getElementById('ventesChart').getContext('2d');
        const ventesChart = new Chart(ctxVentes, {
            type: 'doughnut',
            data: {
                labels: ['À venir', 'En cours', 'Clôturées'],
                datasets: [{
                    data: [
                        <?= $statsVentes['a_venir']; ?>,
                        <?= $statsVentes['en_cours']; ?>,
                        <?= $statsVentes['cloturee']; ?>
                    ],
                    backgroundColor: [
                        '#f1c40f', // yellow
                        '#2ecc71', // green
                        '#e74c3c'  // red
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom' },
                    title: { display: true, text: 'Répartition des statuts de ventes' }
                }
            }
        });

        // Graphique Vue d'ensemble (Barres)
        const ctxCa = document.getElementById('caChart').getContext('2d');
        const caChart = new Chart(ctxCa, {
            type: 'bar',
            data: {
                labels: ['Utilisateurs', 'Articles', 'Ventes Totales'],
                datasets: [{
                    label: 'Quantité',
                    data: [
                        <?= $nbUtilisateurs; ?>,
                        <?= $nbArticles; ?>,
                        <?= $totalVentes; ?>
                    ],
                    backgroundColor: '#3498db',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    title: { display: true, text: 'Vue d\'ensemble de la plateforme' }
                },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
        // Graphique Évolution des enchères (7 derniers jours)
        const ctxEvolution = document.getElementById('evolutionChart').getContext('2d');
        const evolutionChart = new Chart(ctxEvolution, {
            type: 'line',
            data: {
                labels: [<?php foreach ($evolutionEncheres as $e) {
    echo "'" . date('d/m', strtotime($e->jour)) . "',";
}?>],
                datasets: [{
                    label: 'Enchères par jour',
                    data: [<?php foreach ($evolutionEncheres as $e) {
    echo $e->total . ",";
}?>],
                    borderColor: '#3498db',
                    backgroundColor: 'rgba(52, 152, 219, 0.1)',
                    fill: true,
                    tension: 0.3,
                    borderWidth: 2,
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: { display: true, text: 'Évolution des enchères (7 derniers jours)' },
                    legend: { display: false }
                },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    </script>
</body>

</html>