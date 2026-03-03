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

        .avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background-color: #3498db;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            margin: 0 auto 15px;
        }

        .user-info {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #ddd;
        }

        .badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
            background-color: #d1ecf1;
            color: #0c5460;
        }

        input[type=text],
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

        .email-field {
            width: 100%;
            padding: 10px;
            margin: 5px 0 15px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            background-color: #ecf0f1;
            color: #7f8c8d;
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

    <div class="form-container">
        <div class="user-info">
            <div class="avatar">
                <?= strtoupper(substr($utilisateur->prenom, 0, 1)); ?>
            </div>
            <h1>
                <?= $utilisateur->prenom . ' ' . $utilisateur->nom; ?>
            </h1>
            <span class="badge">
                <?= $utilisateur->role_libelle; ?>
            </span>
            <p style="color: #7f8c8d; margin-top: 10px; font-size: 13px;">
                Membre depuis le
                <?= date('d/m/Y', strtotime($utilisateur->created_at)); ?>
            </p>
        </div>

        <?= form_open('Enchere/modifierProfil') ?>

        <?= form_label('Nom : '); ?>
        <?php echo form_input(['name' => 'nom', 'value' => $utilisateur->nom]); ?> <br />

        <?= form_label('Prénom : '); ?>
        <?php echo form_input(['name' => 'prenom', 'value' => $utilisateur->prenom]); ?> <br />

        <?= form_label('Email : '); ?>
        <div class="email-field">
            <?= $utilisateur->email; ?>
        </div>

        <?= form_label('Téléphone : '); ?>
        <?php echo form_input(['name' => 'telephone', 'type' => 'tel', 'value' => $utilisateur->telephone ?? '']); ?>
        <br />

        <?= form_label('Adresse : '); ?>
        <?php echo form_input(['name' => 'adresse', 'value' => $utilisateur->adresse ?? '']); ?> <br />

        <hr style="margin: 15px 0;">

        <?= form_label('Nouveau mot de passe (laisser vide pour ne pas changer) : '); ?>
        <?php echo form_password(['name' => 'nouveau_mot_de_passe', 'placeholder' => 'Min. 8 caractères']); ?> <br />

        <?php echo form_submit('modifier', 'Enregistrer les modifications'); ?>
        <?= form_close(); ?>
    </div>

    <footer>
        <p>&copy;
            <?= date('Y'); ?> EnchèreAPorter — Ville de Getcet
        </p>
    </footer>
</body>

</html>