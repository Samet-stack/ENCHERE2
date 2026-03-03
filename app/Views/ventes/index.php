<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="container">
    <div class="page-header flex-between">
        <div>
            <h1>🛍️ Ventes aux enchères</h1>
            <p>Découvrez toutes les ventes de vêtements d'occasion</p>
        </div>
        <?php if (session()->get('role') === 'secretaire'): ?>
            <a href="<?= base_url('/ventes/creer') ?>" class="btn btn-primary">+ Nouvelle vente</a>
        <?php endif; ?>
    </div>

    <!-- Filtres -->
    <div class="filter-tabs">
        <a href="<?= base_url('/ventes') ?>" class="filter-tab <?= empty($filtre) ? 'active' : '' ?>">Toutes</a>
        <a href="<?= base_url('/ventes?etat=en_cours') ?>"
            class="filter-tab <?= $filtre === 'en_cours' ? 'active' : '' ?>">🔥 En cours</a>
        <a href="<?= base_url('/ventes?etat=a_venir') ?>"
            class="filter-tab <?= $filtre === 'a_venir' ? 'active' : '' ?>">📅 À venir</a>
        <a href="<?= base_url('/ventes?etat=cloturee') ?>"
            class="filter-tab <?= $filtre === 'cloturee' ? 'active' : '' ?>">✅ Clôturées</a>
    </div>

    <?php if (!empty($ventes)): ?>
        <div class="card-grid">
            <?php foreach ($ventes as $vente): ?>
                <a href="<?= base_url('/ventes/' . $vente['id_vente']) ?>" class="card"
                    style="text-decoration: none; color: inherit;">
                    <div class="card-header">
                        <h3 class="card-title">
                            <?= esc($vente['titre']) ?>
                        </h3>
                        <?php
                        $badgeClass = match ($vente['etat']) {
                            'en_cours' => 'badge-success',
                            'a_venir' => 'badge-warning',
                            'cloturee' => 'badge-danger',
                            default => 'badge-primary',
                        };
                        $badgeLabel = match ($vente['etat']) {
                            'en_cours' => 'En cours',
                            'a_venir' => 'À venir',
                            'cloturee' => 'Clôturée',
                            default => $vente['etat'],
                        };
                        ?>
                        <span class="badge <?= $badgeClass ?>">
                            <?= $badgeLabel ?>
                        </span>
                    </div>
                    <div class="card-body">
                        <p>
                            <?= esc(mb_strimwidth($vente['description'] ?? 'Aucune description', 0, 120, '...')) ?>
                        </p>
                    </div>
                    <div class="card-footer">
                        <div class="card-meta">
                            <span>📅
                                <?= date('d/m/Y H:i', strtotime($vente['date_debut'])) ?>
                            </span>
                            <span>→
                                <?= date('d/m/Y H:i', strtotime($vente['date_fin'])) ?>
                            </span>
                        </div>
                        <span class="btn btn-sm btn-primary">Détails →</span>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <div class="icon">📦</div>
            <h3>Aucune vente trouvée</h3>
            <p>Il n'y a pas de vente correspondant à ce filtre.</p>
        </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>