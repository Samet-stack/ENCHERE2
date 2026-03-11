<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer une vente - EnchèreAPorter</title>
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

        .form-container {
            max-width: 500px;
            margin: 20px auto;
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            border: 1px solid #ddd;
        }

        input[type=text],
        input[type=datetime-local],
        textarea {
            width: 100%;
            padding: 10px;
            margin: 5px 0 15px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        textarea {
            min-height: 100px;
            resize: vertical;
        }

        label {
            font-weight: bold;
            color: #2c3e50;
        }

        input[type=submit] {
            width: 100%;
            padding: 12px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        input[type=submit]:hover {
            background-color: #2980b9;
        }
    </style>
</head>

<body>
    <?= view('nav'); ?>

    <div class="form-container">
        <h1>Créer une vente</h1>

        <?= validation_list_errors() ?>

        <?= form_open('Enchere/validerCreerVente') ?>

        <?= form_label('Titre de la vente * : '); ?>
        <?php echo form_input(['name' => 'titre', 'value' => set_value('titre'), 'placeholder' => 'Ex: Vente de printemps 2026']); ?>
        <br />

        <?= form_label('Description : '); ?>
        <textarea name="description" placeholder="Décrivez la vente..."><?= set_value('description'); ?></textarea>
        <br />

        <?= form_label('Date de début * : '); ?>
        <?php echo form_input(['name' => 'date_debut', 'type' => 'datetime-local', 'value' => set_value('date_debut')]); ?>
        <br />

        <?= form_label('Date de fin * : '); ?>
        <?php echo form_input(['name' => 'date_fin', 'type' => 'datetime-local', 'value' => set_value('date_fin')]); ?>
        <br />

        <?php echo form_submit('creer', 'Créer la vente'); ?>
        <?= form_close(); ?>
    </div>
</body>

</html>