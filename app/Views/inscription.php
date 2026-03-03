<html>

<head>
    <title>Inscription - EnchèreAPorter</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .form-container {
            max-width: 500px;
            margin: 40px auto;
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            border: 1px solid #ddd;
        }

        h1 {
            text-align: center;
            color: #2c3e50;
        }

        input[type=text],
        input[type=email],
        input[type=tel],
        input[type=password] {
            width: 100%;
            padding: 10px;
            margin: 5px 0 15px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
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

        .lien {
            text-align: center;
            margin-top: 15px;
        }

        .lien a {
            color: #3498db;
        }
    </style>
</head>

<body>
    <div class="form-container">
        <h1>Inscription</h1>
        <p style="text-align: center; color: #7f8c8d;">Rejoignez la communauté EnchèreAPorter</p>

        <?= validation_list_errors() ?>

        <?= form_open('Enchere/validerInscription') ?>

        <?= form_label('Nom * : '); ?>
        <?php echo form_input(['name' => 'nom', 'value' => set_value('nom'), 'placeholder' => 'Votre nom']); ?> <br />

        <?= form_label('Prénom * : '); ?>
        <?php echo form_input(['name' => 'prenom', 'value' => set_value('prenom'), 'placeholder' => 'Votre prénom']); ?>
        <br />

        <?= form_label('Email * : '); ?>
        <?php echo form_input(['name' => 'email', 'type' => 'email', 'value' => set_value('email'), 'placeholder' => 'votre@email.fr']); ?>
        <br />

        <?= form_label('Adresse (ville de Getcet) * : '); ?>
        <?php echo form_input(['name' => 'adresse', 'value' => set_value('adresse'), 'placeholder' => 'Votre adresse à Getcet']); ?>
        <br />

        <?= form_label('Téléphone : '); ?>
        <?php echo form_input(['name' => 'telephone', 'type' => 'tel', 'value' => set_value('telephone'), 'placeholder' => '06 12 34 56 78']); ?>
        <br />

        <?= form_label('Mot de passe * (min. 8 caractères) : '); ?>
        <?php echo form_password(['name' => 'mot_de_passe', 'placeholder' => 'Mot de passe sécurisé']); ?> <br />

        <?= form_label('Confirmer le mot de passe * : '); ?>
        <?php echo form_password(['name' => 'confirm_password', 'placeholder' => 'Confirmer le mot de passe']); ?>
        <br />

        <?php echo form_submit('valider', "Créer mon compte"); ?>
        <?= form_close(); ?>

        <div class="lien">
            Déjà un compte ?
            <?= anchor('Enchere/connexion', 'Se connecter'); ?>
        </div>
    </div>
</body>

</html>