<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="auth-page">
    <div class="auth-card">
        <h2>🔐 Connexion</h2>
        <p class="subtitle">Accédez à votre espace EnchèreAPorter</p>

        <form action="<?= base_url('/connexion') ?>" method="post">
            <?= csrf_field() ?>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control" value="<?= old('email') ?>"
                    placeholder="votre@email.fr" required>
            </div>

            <div class="form-group">
                <label for="mot_de_passe">Mot de passe</label>
                <input type="password" id="mot_de_passe" name="mot_de_passe" class="form-control"
                    placeholder="Votre mot de passe" required>
            </div>

            <button type="submit" class="btn btn-primary btn-block btn-lg">Se connecter</button>
        </form>

        <div class="auth-link">
            Pas encore de compte ? <a href="<?= base_url('/inscription') ?>">S'inscrire</a>
        </div>
    </div>
</div>
<?= $this->endSection() ?>