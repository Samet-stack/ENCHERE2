<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

        .erreur-msg {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
            text-align: center;
        }

        .info-msg {
            background-color: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
            padding: 8px;
            border-radius: 4px;
            margin-bottom: 10px;
            font-size: 13px;
        }
    </style>
</head>

<body>
    <div class="form-container">
        <h1>Inscription</h1>
        <p style="text-align: center; color: #7f8c8d;">Rejoignez la communauté EnchèreAPorter</p>

        <div class="info-msg">
            ⚠️ Seuls les habitants de la ville de <strong>Getcet</strong> (code postal <strong>99999</strong>) peuvent s'inscrire.
        </div>

        <?php if (isset($erreur)): ?>
            <div class="erreur-msg"><?= $erreur; ?></div>
        <?php endif; ?>

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

        <?= form_label('Adresse * : '); ?>
        <?php echo form_input(['name' => 'adresse', 'value' => set_value('adresse'), 'placeholder' => 'Votre adresse à Getcet']); ?>
        <br />

        <?= form_label('Ville * : '); ?>
        <?php echo form_input(['name' => 'ville', 'value' => set_value('ville', 'Getcet'), 'placeholder' => 'Getcet']); ?>
        <br />

        <?= form_label('Code postal * : '); ?>
        <?php echo form_input(['name' => 'code_postal', 'value' => set_value('code_postal', '99999'), 'placeholder' => '99999', 'maxlength' => '5']); ?>
        <br />

        <?= form_label('Téléphone : '); ?>
        <?php echo form_input(['name' => 'telephone', 'type' => 'tel', 'value' => set_value('telephone'), 'placeholder' => '06 12 34 56 78']); ?>
        <br />

        <?= form_label('Mot de passe * (min. 8 car., 1 majuscule, 1 minuscule, 1 chiffre, 1 spécial) : '); ?>
        <?php echo form_password(['name' => 'mot_de_passe', 'placeholder' => 'Ex: MonPass1!']); ?> <br />

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
