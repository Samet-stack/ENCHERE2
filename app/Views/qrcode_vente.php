<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code -
        <?= $vente->titre; ?>
    </title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            text-align: center;
        }

        h1,
        h2 {
            color: #2c3e50;
        }

        .container {
            padding: 40px;
        }

        img {
            border-radius: 8px;
            margin: 20px 0;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin: 5px;
        }

        .btn:hover {
            background-color: #2980b9;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>QR Code</h1>
        <h2>
            <?= $vente->titre; ?>
        </h2>
        <p>Scannez ce QR code pour accéder à cette vente</p>

        <br />
        <img src="<?= $qrCodeUrl; ?>" alt="QR Code de la vente" width="300" height="300">
        <br /><br />

        <?= anchor($venteUrl, 'Lien direct vers la vente', ['class' => 'btn']); ?>
        <br />
        <?= anchor('Enchere/detailVente/' . $vente->id_vente, '← Retour à la vente', ['class' => 'btn']); ?>
    </div>
</body>

</html>