<html>

<head>
    <title><?= $titre; ?></title>
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

        .nav-right a {
            float: none;
        }

        h1 {
            color: #2c3e50;
            margin: 20px;
        }

        h2 {
            color: #2c3e50;
            margin: 15px 20px;
        }

        .container {
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .hero {
            text-align: center;
            padding: 40px;
            background-color: #2c3e50;
            color: white;
            margin-bottom: 20px;
            border-radius: 8px;
        }

        .hero h1 {
            color: white;
        }

        .hero p {
            font-size: 18px;
            margin: 10px 0;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin: 5px;
            border: none;
            cursor: pointer;
            font-size: 14px;
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

        .btn-small {
            padding: 5px 12px;
            font-size: 12px;
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

        .erreur {
            color: #e74c3c;
            background-color: #fce4e1;
            padding: 10px;
            border-radius: 4px;
            margin: 10px 20px;
        }

        .succes {
            color: #27ae60;
            background-color: #d4edda;
            padding: 10px;
            border-radius: 4px;
            margin: 10px 20px;
        }

        .form-container {
            max-width: 500px;
            margin: 20px auto;
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            border: 1px solid #ddd;
        }

        .prix {
            color: #27ae60;
            font-weight: bold;
            font-size: 18px;
        }

        .article-photo {
            max-width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 4px;
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

        footer {
            background-color: #2c3e50;
            color: white;
            text-align: center;
            padding: 15px;
            margin-top: 30px;
        }

        footer p {
            margin: 5px;
            font-size: 14px;
        }

        input[type=text],
        input[type=email],
        input[type=tel],
        input[type=number],
        input[type=password],
        input[type=datetime-local],
        input[type=file],
        textarea,
        select {
            width: 100%;
            padding: 8px;
            margin: 5px 0 15px 0;
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
        }
    </style>
</head>

<body>
    <?= view('nav'); ?>


    <!-- Hero -->
    <div class="hero">
        <h1>EnchèreAPorter</h1>
        <p><em>« Clique vite sinon c'est ton voisin qui le porte ! »</em></p>
        <p>Enchérissez sur des vêtements d'occasion de la friperie municipale Fripouilles</p>
        <?= anchor('Enchere/listeVentes', 'Voir les ventes', ['class' => 'btn']); ?>
        <?php if (!session()->get('estConnecte')): ?>
            <?= anchor('Enchere/inscription', "S'inscrire", ['class' => 'btn btn-success']); ?>
        <?php endif; ?>
    </div>

    <div class="container">
        <!-- Ventes en cours -->
        <?php if (!empty($ventesEnCours)): ?>
            <h2>Ventes en cours</h2>
            <div class="card-grid">
                <?php foreach ($ventesEnCours as $vente): ?>
                    <div class="card card-item">
                        <h3><?= $vente->titre; ?></h3>
                        <span class="badge badge-success">En cours</span>
                        <p><?= $vente->description ?? 'Aucune description'; ?></p>
                        <p>Fin : <?= date('d/m/Y H:i', strtotime($vente->date_fin)); ?></p>
                        <?= anchor('Enchere/detailVente/' . $vente->id_vente, 'Voir détails →', ['class' => 'btn btn-small']); ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Ventes à venir -->
        <?php if (!empty($ventesAVenir)): ?>
            <h2>Ventes à venir</h2>
            <div class="card-grid">
                <?php foreach ($ventesAVenir as $vente): ?>
                    <div class="card card-item">
                        <h3><?= $vente->titre; ?></h3>
                        <span class="badge badge-warning">À venir</span>
                        <p><?= $vente->description ?? 'Aucune description'; ?></p>
                        <p>Début : <?= date('d/m/Y H:i', strtotime($vente->date_debut)); ?></p>
                        <?= anchor('Enchere/detailVente/' . $vente->id_vente, 'Voir détails →', ['class' => 'btn btn-small']); ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (empty($ventesEnCours) && empty($ventesAVenir)): ?>
            <p style="text-align: center; padding: 40px; color: #7f8c8d;">Aucune vente pour le moment. Revenez bientôt !</p>
        <?php endif; ?>
    </div>

    <footer>
        <p>&copy; <?= date('Y'); ?> <strong>EnchèreAPorter</strong> — Ville de Getcet | Friperie « Fripouilles »</p>
    </footer>
</body>

</html>