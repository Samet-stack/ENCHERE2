<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="container">
    <div class="page-header flex-between">
        <div>
            <h1>👕 Articles</h1>
            <p>Gérer les articles de la friperie Fripouilles</p>
        </div>
        <a href="<?= base_url('/articles/creer') ?>" class="btn btn-primary">+ Ajouter un article</a>
    </div>

    <?php if (!empty($articles)): ?>
        <div class="card-grid">
            <?php foreach ($articles as $article): ?>
                <div class="card">
                    <?php if (!empty($article['photo'])): ?>
                        <img src="<?= base_url($article['photo']) ?>" alt="<?= esc($article['libelle']) ?>" class="article-photo">
                    <?php else: ?>
                        <div class="article-no-photo">👕</div>
                    <?php endif; ?>

                    <div class="card-header">
                        <h3 class="card-title">
                            <?= esc($article['libelle']) ?>
                        </h3>
                        <span class="badge badge-info">
                            <?= esc($article['taille'] ?? 'N/A') ?>
                        </span>
                    </div>

                    <div class="card-body">
                        <p>
                            <?= esc($article['description'] ?? 'Aucune description') ?>
                        </p>
                    </div>

                    <div class="card-footer">
                        <span class="badge badge-primary">
                            <?= esc($article['etat']) ?>
                        </span>
                        <span class="prix">
                            <?= number_format($article['prix_origine'], 2) ?> €
                        </span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <div class="icon">👕</div>
            <h3>Aucun article</h3>
            <p>Ajoutez des articles pour les mettre en vente.</p>
        </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>