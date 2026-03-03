<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="container">
    <div class="page-header">
        <h1>➕ Créer une vente</h1>
        <p>Définissez les paramètres de la nouvelle vente aux enchères</p>
    </div>

    <div class="form-container">
        <div class="card" style="padding: 2rem;">
            <form action="<?= base_url('/ventes/creer') ?>" method="post">
                <?= csrf_field() ?>

                <div class="form-group">
                    <label for="titre">Titre de la vente *</label>
                    <input type="text" id="titre" name="titre" class="form-control" value="<?= old('titre') ?>"
                        placeholder="Ex: Vente de printemps 2026" required>
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" class="form-control"
                        placeholder="Décrivez la vente..."><?= old('description') ?></textarea>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label for="date_debut">Date de début *</label>
                        <input type="datetime-local" id="date_debut" name="date_debut" class="form-control"
                            value="<?= old('date_debut') ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="date_fin">Date de fin *</label>
                        <input type="datetime-local" id="date_fin" name="date_fin" class="form-control"
                            value="<?= old('date_fin') ?>" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-block btn-lg mt-2">Créer la vente</button>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>