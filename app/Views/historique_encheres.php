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
            max-width: 1000px;
            margin: 0 auto;
        }

        .btn {
            display: inline-block;
            padding: 6px 14px;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin: 2px;
            font-size: 13px;
        }

        .btn-danger {
            background-color: #e74c3c;
        }

        .btn-danger:hover {
            background-color: #c0392b;
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

        .prix {
            color: #27ae60;
            font-weight: bold;
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

        .badge-danger {
            background-color: #f8d7da;
            color: #721c24;
        }

        @media (max-width: 768px) {
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
        <h1>Historique de mes enchères</h1>

        <?php if (!empty($encheres)): ?>
            <table>
                <tr>
                    <th>Date</th>
                    <th>Vente</th>
                    <th>Article</th>
                    <th>Montant</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
                <?php foreach ($encheres as $enchere): ?>
                    <tr>
                        <td>
                            <?= date('d/m/Y H:i', strtotime($enchere->date_enchere)); ?>
                        </td>
                        <td>
                            <?= $enchere->vente_titre; ?>
                        </td>
                        <td>
                            <?= $enchere->article_libelle; ?>
                        </td>
                        <td class="prix">
                            <?= number_format($enchere->montant, 2); ?> €
                        </td>
                        <td>
                            <?php if ($enchere->est_annulee): ?>
                                <span class="badge badge-danger">Annulée</span>
                            <?php else: ?>
                                <span class="badge badge-success">Active</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if (!$enchere->est_annulee && $enchere->vente_etat === 'en_cours'): ?>
                                <?= anchor('Enchere/annulerEnchere/' . $enchere->id_enchere, 'Annuler', ['class' => 'btn btn-danger', 'onclick' => "return confirm('Annuler cette enchère ?')"]); ?>
                            <?php else: ?>
                                —
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p style="text-align: center; padding: 30px; color: #7f8c8d;">Aucune enchère.
                <?= anchor('Enchere/listeVentes', 'Voir les ventes'); ?>
            </p>
        <?php endif; ?>
    </div>

    <footer>
        <p>&copy;
            <?= date('Y'); ?> EnchèreAPorter — Ville de Getcet
        </p>
    </footer>
</body>

</html>