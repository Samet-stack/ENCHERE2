<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="container">
    <div class="page-header">
        <h1>📜 Historique de mes enchères</h1>
        <p>Retrouvez toutes vos enchères passées et en cours</p>
    </div>

    <?php if (!empty($encheres)): ?>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Vente</th>
                        <th>Article</th>
                        <th>Montant</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($encheres as $enchere): ?>
                        <tr>
                            <td>
                                <?= date('d/m/Y H:i', strtotime($enchere['date_enchere'])) ?>
                            </td>
                            <td>
                                <?= esc($enchere['vente_titre']) ?>
                            </td>
                            <td>
                                <?= esc($enchere['article_libelle']) ?>
                            </td>
                            <td><span class="prix">
                                    <?= number_format($enchere['montant'], 2) ?> €
                                </span></td>
                            <td>
                                <?php if ($enchere['est_annulee']): ?>
                                    <span class="badge badge-danger">Annulée</span>
                                <?php else: ?>
                                    <span class="badge badge-success">Active</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!$enchere['est_annulee']): ?>
                                    <a href="<?= base_url('/encheres/annuler/' . $enchere['id_enchere']) ?>"
                                        class="btn btn-sm btn-danger"
                                        onclick="return confirm('Annuler cette enchère ?')">Annuler</a>
                                <?php else: ?>
                                    <span style="color: var(--text-muted);">—</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <div class="icon">💰</div>
            <h3>Aucune enchère</h3>
            <p>Vous n'avez pas encore enchéri sur un article.</p>
            <a href="<?= base_url('/ventes') ?>" class="btn btn-primary mt-2">Voir les ventes</a>
        </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>