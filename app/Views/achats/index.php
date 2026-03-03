<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="container">
    <div class="page-header">
        <h1>🛒 Mes achats</h1>
        <p>Articles remportés aux enchères</p>
    </div>

    <?php if (!empty($achats)): ?>
        <div class="card-grid">
            <?php foreach ($achats as $achat): ?>
                <div class="card">
                    <?php if (!empty($achat['photo'])): ?>
                        <img src="<?= base_url($achat['photo']) ?>" alt="<?= esc($achat['article_libelle']) ?>"
                            class="article-photo">
                    <?php else: ?>
                        <div class="article-no-photo">🎉</div>
                    <?php endif; ?>

                    <div class="card-header">
                        <h3 class="card-title">
                            <?= esc($achat['article_libelle']) ?>
                        </h3>
                        <?php if ($achat['confirme']): ?>
                            <span class="badge badge-success">Confirmé</span>
                        <?php else: ?>
                            <span class="badge badge-warning">En attente</span>
                        <?php endif; ?>
                    </div>

                    <div class="card-body">
                        <p>Vente :
                            <?= esc($achat['vente_titre']) ?>
                        </p>
                        <p class="prix mt-1">Montant :
                            <?= number_format($achat['montant_final'], 2) ?> €
                        </p>
                    </div>

                    <?php if (!$achat['confirme']): ?>
                        <div class="card-footer">
                            <form action="<?= base_url('/achats/confirmer/' . $achat['id_achat']) ?>" method="post"
                                style="width: 100%;">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-success btn-block">✅ Confirmer l'achat</button>
                            </form>
                        </div>
                    <?php else: ?>
                        <div class="card-footer">
                            <span style="color: var(--success); font-size: 0.85rem;">
                                Confirmé le
                                <?= date('d/m/Y à H:i', strtotime($achat['date_confirmation'])) ?>
                            </span>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <div class="icon">🛒</div>
            <h3>Aucun achat</h3>
            <p>Vous n'avez pas encore remporté d'enchère.</p>
            <a href="<?= base_url('/ventes') ?>" class="btn btn-primary mt-2">Voir les ventes</a>
        </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>