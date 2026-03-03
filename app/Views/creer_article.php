<html>

<head>
    <title>Ajouter un article - EnchèreAPorter</title>
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
            text-align: center;
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
        input[type=number],
        input[type=file],
        textarea,
        select {
            width: 100%;
            padding: 10px;
            margin: 5px 0 15px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        textarea {
            min-height: 80px;
            resize: vertical;
        }

        label {
            font-weight: bold;
            color: #2c3e50;
        }

        input[type=submit] {
            width: 100%;
            padding: 12px;
            background-color: #27ae60;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        input[type=submit]:hover {
            background-color: #219a52;
        }
    </style>
</head>

<body>
    <?= view('nav'); ?>

    <div class="form-container">
        <h1>Ajouter un article</h1>

        <?= validation_list_errors() ?>

        <?= form_open_multipart('Enchere/validerCreerArticle') ?>

        <?= form_label('Libellé * : '); ?>
        <?php echo form_input(['name' => 'libelle', 'value' => set_value('libelle'), 'placeholder' => 'Ex: Veste en jean Levis']); ?>
        <br />

        <?= form_label('Description : '); ?>
        <textarea name="description" placeholder="Décrivez l'article..."><?= set_value('description'); ?></textarea>
        <br />

        <?= form_label('Taille : '); ?>
        <select name="taille">
            <option value="">-- Sélectionner --</option>
            <option value="XS" <?= set_value('taille') === 'XS' ? 'selected' : ''; ?>>XS</option>
            <option value="S" <?= set_value('taille') === 'S' ? 'selected' : ''; ?>>S</option>
            <option value="M" <?= set_value('taille') === 'M' ? 'selected' : ''; ?>>M</option>
            <option value="L" <?= set_value('taille') === 'L' ? 'selected' : ''; ?>>L</option>
            <option value="XL" <?= set_value('taille') === 'XL' ? 'selected' : ''; ?>>XL</option>
            <option value="XXL" <?= set_value('taille') === 'XXL' ? 'selected' : ''; ?>>XXL</option>
            <option value="36" <?= set_value('taille') === '36' ? 'selected' : ''; ?>>36</option>
            <option value="38" <?= set_value('taille') === '38' ? 'selected' : ''; ?>>38</option>
            <option value="40" <?= set_value('taille') === '40' ? 'selected' : ''; ?>>40</option>
            <option value="42" <?= set_value('taille') === '42' ? 'selected' : ''; ?>>42</option>
            <option value="44" <?= set_value('taille') === '44' ? 'selected' : ''; ?>>44</option>
        </select> <br />

        <?= form_label('État * : '); ?>
        <select name="etat">
            <option value="bon" <?= set_value('etat') === 'bon' ? 'selected' : ''; ?>>Bon</option>
            <option value="très bon" <?= set_value('etat') === 'très bon' ? 'selected' : ''; ?>>Très bon</option>
            <option value="comme neuf" <?= set_value('etat') === 'comme neuf' ? 'selected' : ''; ?>>Comme neuf</option>
        </select> <br />

        <?= form_label("Prix d'origine (€) * : "); ?>
        <?php echo form_input(['name' => 'prix_origine', 'type' => 'number', 'step' => '0.01', 'min' => '0', 'value' => set_value('prix_origine'), 'placeholder' => '29.99']); ?>
        <br />

        <?= form_label('Photo : '); ?>
        <?php echo form_input(['name' => 'photo', 'type' => 'file', 'accept' => 'image/*']); ?> <br />

        <?php echo form_submit('creer', "Ajouter l'article"); ?>
        <?= form_close(); ?>
    </div>
</body>

</html>