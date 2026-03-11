<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - EnchèreAPorter</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .form-container {
            max-width: 400px;
            margin: 80px auto;
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            border: 1px solid #ddd;
        }

        h1 {
            text-align: center;
            color: #2c3e50;
        }

        .erreur {
            color: #e74c3c;
            background-color: #fce4e1;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
        }

        input[type=email],
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
        <h1>Connexion</h1>

        <?php if (isset($erreur)): ?>
            <div class="erreur">
                <?= $erreur; ?>
            </div>
        <?php endif; ?>

        <?= form_open('Enchere/connecter') ?>

        <?= form_label('Email : '); ?>
        <?php echo form_input(['name' => 'email', 'type' => 'email', 'value' => set_value('email'), 'placeholder' => 'votre@email.fr']); ?>
        <br />

        <?= form_label('Mot de passe : '); ?>
        <?php echo form_password(['name' => 'mot_de_passe', 'placeholder' => 'Votre mot de passe']); ?> <br />

        <?php echo form_submit('connexion', 'Se connecter'); ?>
        <?= form_close(); ?>

        <div class="lien">
            Pas encore de compte ?
            <?= anchor('Enchere/inscription', "S'inscrire"); ?>
        </div>
    </div>
</body>

</html>