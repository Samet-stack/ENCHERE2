<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="auth-page">
    <div class="auth-card">
        <h2>📝 Inscription</h2>
        <p class="subtitle">Rejoignez la communauté EnchèreAPorter</p>

        <form action="<?= base_url('/inscription') ?>" method="post">
            <?= csrf_field() ?>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label for="nom">Nom *</label>
                    <input type="text" id="nom" name="nom" class="form-control" value="<?= old('nom') ?>"
                        placeholder="Votre nom" required>
                </div>
                <div class="form-group">
                    <label for="prenom">Prénom *</label>
                    <input type="text" id="prenom" name="prenom" class="form-control" value="<?= old('prenom') ?>"
                        placeholder="Votre prénom" required>
                </div>
            </div>

            <div class="form-group">
                <label for="email">Email *</label>
                <input type="email" id="email" name="email" class="form-control" value="<?= old('email') ?>"
                    placeholder="votre@email.fr" required>
            </div>

            <div class="form-group">
                <label for="adresse">Adresse (ville de Getcet) *</label>
                <input type="text" id="adresse" name="adresse" class="form-control" value="<?= old('adresse') ?>"
                    placeholder="Votre adresse à Getcet" required>
            </div>

            <div class="form-group">
                <label for="telephone">Téléphone</label>
                <input type="tel" id="telephone" name="telephone" class="form-control" value="<?= old('telephone') ?>"
                    placeholder="06 12 34 56 78">
            </div>

            <div class="form-group">
                <label for="mot_de_passe">Mot de passe * (min. 8 caractères)</label>
                <input type="password" id="mot_de_passe" name="mot_de_passe" class="form-control"
                    placeholder="Mot de passe sécurisé" required minlength="8">
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirmer le mot de passe *</label>
                <input type="password" id="confirm_password" name="confirm_password" class="form-control"
                    placeholder="Confirmer votre mot de passe" required>
            </div>

            <button type="submit" class="btn btn-primary btn-block btn-lg">Créer mon compte</button>
        </form>

        <div class="auth-link">
            Déjà un compte ? <a href="<?= base_url('/connexion') ?>">Se connecter</a>
        </div>
    </div>
</div>
<?= $this->endSection() ?>