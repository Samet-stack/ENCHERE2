<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="container">
    <div class="page-header">
        <h1>📊 Tableau de bord</h1>
        <p>Vue d'ensemble de la plateforme EnchèreAPorter</p>
    </div>

    <!-- Statistiques -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-value">
                <?= $totalVentes ?>
            </div>
            <div class="stat-label">Ventes totales</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">
                <?= $statsVentes['en_cours'] ?>
            </div>
            <div class="stat-label">Ventes en cours</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">
                <?= number_format($montantTotal, 2) ?> €
            </div>
            <div class="stat-label">Revenus confirmés</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">
                <?= $nbUtilisateurs ?>
            </div>
            <div class="stat-label">Utilisateurs</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">
                <?= $nbArticles ?>
            </div>
            <div class="stat-label">Articles</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">
                <?= $statsVentes['a_venir'] ?>
            </div>
            <div class="stat-label">Ventes à venir</div>
        </div>
    </div>

    <!-- Actions rapides -->
    <div class="card mb-3" style="padding: 1.5rem;">
        <h3 style="margin-bottom: 1rem;">⚡ Actions rapides</h3>
        <div class="flex gap-1" style="flex-wrap: wrap;">
            <a href="<?= base_url('/ventes/creer') ?>" class="btn btn-primary">+ Nouvelle vente</a>
            <a href="<?= base_url('/articles/creer') ?>" class="btn btn-secondary">+ Nouvel article</a>
            <a href="<?= base_url('/ventes') ?>" class="btn btn-secondary">📋 Voir les ventes</a>
            <a href="<?= base_url('/articles') ?>" class="btn btn-secondary">👕 Voir les articles</a>
        </div>
    </div>

    <!-- Dernières ventes -->
    <h2 class="section-title">📋 Dernières ventes</h2>
    <?php if (!empty($dernieresVentes)): ?>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>État</th>
                        <th>Début</th>
                        <th>Fin</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($dernieresVentes as $vente): ?>
                        <tr>
                            <td><strong>
                                    <?= esc($vente['titre']) ?>
                                </strong></td>
                            <td>
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
                            </td>
                            <td>
                                <?= date('d/m/Y H:i', strtotime($vente['date_debut'])) ?>
                            </td>
                            <td>
                                <?= date('d/m/Y H:i', strtotime($vente['date_fin'])) ?>
                            </td>
                            <td>
                                <div class="flex gap-1">
                                    <a href="<?= base_url('/ventes/' . $vente['id_vente']) ?>"
                                        class="btn btn-sm btn-secondary">Détails</a>
                                    <a href="<?= base_url('/ventes/qrcode/' . $vente['id_vente']) ?>"
                                        class="btn btn-sm btn-secondary">QR</a>
                                    <?php if ($vente['etat'] !== 'cloturee'): ?>
                                        <a href="<?= base_url('/ventes/cloturer/' . $vente['id_vente']) ?>"
                                            class="btn btn-sm btn-danger" onclick="return confirm('Clôturer ?')">Clôturer</a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <div class="icon">📊</div>
            <h3>Aucune vente</h3>
            <p>Créez votre première vente aux enchères.</p>
        </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>