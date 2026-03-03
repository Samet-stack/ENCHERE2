<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="container">
    <div class="page-header">
        <h1>👤 Mon profil</h1>
        <p>Gérez vos informations personnelles</p>
    </div>

    <div class="form-container">
        <div class="card" style="padding: 2rem;">
            <!-- Infos actuelles -->
            <div
                style="text-align: center; margin-bottom: 2rem; padding-bottom: 1.5rem; border-bottom: 1px solid var(--border);">
                <div
                    style="width: 80px; height: 80px; border-radius: 50%; background: linear-gradient(135deg, var(--primary), var(--secondary)); display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; font-size: 2rem;">
                    <?= strtoupper(substr($utilisateur['prenom'], 0, 1)) ?>
                </div>
                <h2>
                    <?= esc($utilisateur['prenom'] . ' ' . $utilisateur['nom']) ?>
                </h2>
                <span class="badge badge-primary" style="margin-top: 0.5rem;">
                    <?= esc($utilisateur['role_libelle']) ?>
                </span>
                <p style="color: var(--text-muted); margin-top: 0.5rem; font-size: 0.85rem;">
                    Membre depuis le
                    <?= date('d/m/Y', strtotime($utilisateur['created_at'])) ?>
                </p>
            </div>

            <form action="<?= base_url('/profil/modifier') ?>" method="post">
                <?= csrf_field() ?>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label for="nom">Nom</label>
                        <input type="text" id="nom" name="nom" class="form-control"
                            value="<?= esc($utilisateur['nom']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="prenom">Prénom</label>
                        <input type="text" id="prenom" name="prenom" class="form-control"
                            value="<?= esc($utilisateur['prenom']) ?>" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" class="form-control" value="<?= esc($utilisateur['email']) ?>"
                        disabled style="opacity: 0.6;">
                    <small style="color: var(--text-muted);">L'email ne peut pas être modifié</small>
                </div>

                <div class="form-group">
                    <label for="telephone">Téléphone</label>
                    <input type="tel" id="telephone" name="telephone" class="form-control"
                        value="<?= esc($utilisateur['telephone'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label for="adresse">Adresse</label>
                    <input type="text" id="adresse" name="adresse" class="form-control"
                        value="<?= esc($utilisateur['adresse'] ?? '') ?>">
                </div>

                <hr style="border-color: var(--border); margin: 1.5rem 0;">

                <div class="form-group">
                    <label for="nouveau_mot_de_passe">Nouveau mot de passe (laisser vide pour ne pas changer)</label>
                    <input type="password" id="nouveau_mot_de_passe" name="nouveau_mot_de_passe" class="form-control"
                        placeholder="Min. 8 caractères" minlength="8">
                </div>

                <button type="submit" class="btn btn-primary btn-block btn-lg">Enregistrer les modifications</button>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>