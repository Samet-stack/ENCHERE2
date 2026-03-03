<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="container">
    <div class="page-header">
        <h1>➕ Ajouter un article</h1>
        <p>Enregistrez un nouveau vêtement dans le catalogue</p>
    </div>

    <div class="form-container">
        <div class="card" style="padding: 2rem;">
            <form action="<?= base_url('/articles/creer') ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>

                <div class="form-group">
                    <label for="libelle">Libellé *</label>
                    <input type="text" id="libelle" name="libelle" class="form-control" value="<?= old('libelle') ?>"
                        placeholder="Ex: Veste en jean Levis" required>
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" class="form-control"
                        placeholder="Décrivez l'article..."><?= old('description') ?></textarea>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label for="taille">Taille</label>
                        <select id="taille" name="taille" class="form-control">
                            <option value="">-- Sélectionner --</option>
                            <option value="XS" <?= old('taille') === 'XS' ? 'selected' : '' ?>>XS</option>
                            <option value="S" <?= old('taille') === 'S' ? 'selected' : '' ?>>S</option>
                            <option value="M" <?= old('taille') === 'M' ? 'selected' : '' ?>>M</option>
                            <option value="L" <?= old('taille') === 'L' ? 'selected' : '' ?>>L</option>
                            <option value="XL" <?= old('taille') === 'XL' ? 'selected' : '' ?>>XL</option>
                            <option value="XXL" <?= old('taille') === 'XXL' ? 'selected' : '' ?>>XXL</option>
                            <option value="36" <?= old('taille') === '36' ? 'selected' : '' ?>>36</option>
                            <option value="38" <?= old('taille') === '38' ? 'selected' : '' ?>>38</option>
                            <option value="40" <?= old('taille') === '40' ? 'selected' : '' ?>>40</option>
                            <option value="42" <?= old('taille') === '42' ? 'selected' : '' ?>>42</option>
                            <option value="44" <?= old('taille') === '44' ? 'selected' : '' ?>>44</option>
                            <option value="46" <?= old('taille') === '46' ? 'selected' : '' ?>>46</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="etat">État *</label>
                        <select id="etat" name="etat" class="form-control" required>
                            <option value="bon" <?= old('etat') === 'bon' ? 'selected' : '' ?>>Bon</option>
                            <option value="très bon" <?= old('etat') === 'très bon' ? 'selected' : '' ?>>Très bon</option>
                            <option value="comme neuf" <?= old('etat') === 'comme neuf' ? 'selected' : '' ?>>Comme neuf
                            </option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="prix_origine">Prix d'origine (€) *</label>
                    <input type="number" id="prix_origine" name="prix_origine" class="form-control" step="0.01" min="0"
                        value="<?= old('prix_origine') ?>" placeholder="Ex: 29.99" required>
                </div>

                <div class="form-group">
                    <label for="photo">Photo</label>
                    <input type="file" id="photo" name="photo" class="form-control" accept="image/*">
                </div>

                <button type="submit" class="btn btn-primary btn-block btn-lg mt-2">Ajouter l'article</button>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>