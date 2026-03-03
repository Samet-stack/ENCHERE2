<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="container">
    <div class="page-header flex-between">
        <div>
            <h1>
                <?= esc($vente['titre']) ?>
            </h1>
            <p>Organisée par
                <?= esc($vente['secretaire_prenom'] . ' ' . $vente['secretaire_nom']) ?>
            </p>
        </div>
        <div class="flex gap-1">
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
            <span class="badge <?= $badgeClass ?>" style="font-size: 0.9rem; padding: 0.4rem 1rem;">
                <?= $badgeLabel ?>
            </span>

            <?php if (session()->get('role') === 'secretaire'): ?>
                <a href="<?= base_url('/ventes/qrcode/' . $vente['id_vente']) ?>" class="btn btn-sm btn-secondary">📱 QR
                    Code</a>
                <?php if ($vente['etat'] !== 'cloturee'): ?>
                    <a href="<?= base_url('/ventes/cloturer/' . $vente['id_vente']) ?>" class="btn btn-sm btn-danger"
                        onclick="return confirm('Êtes-vous sûr de vouloir clôturer cette vente ?')">🔒 Clôturer</a>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="detail-grid">
        <div class="detail-main">
            <!-- Description -->
            <?php if (!empty($vente['description'])): ?>
                <div class="card mb-2">
                    <h3 style="margin-bottom: 0.75rem;">📝 Description</h3>
                    <p class="card-body">
                        <?= nl2br(esc($vente['description'])) ?>
                    </p>
                </div>
            <?php endif; ?>

            <!-- Articles de la vente -->
            <h2 class="section-title">👕 Articles en vente</h2>

            <?php if (!empty($articles)): ?>
                <div class="card-grid">
                    <?php foreach ($articles as $article): ?>
                        <div class="card">
                            <?php if (!empty($article['photo'])): ?>
                                <img src="<?= base_url($article['photo']) ?>" alt="<?= esc($article['libelle']) ?>"
                                    class="article-photo">
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
                                    <?= esc($article['description'] ?? '') ?>
                                </p>
                                <div class="mt-1">
                                    <span class="badge badge-primary">
                                        <?= esc($article['etat']) ?>
                                    </span>
                                </div>
                            </div>

                            <div class="card-footer">
                                <div>
                                    <span class="prix-origine">Prix origine :
                                        <?= number_format($article['prix_origine'], 2) ?> €
                                    </span><br>
                                    <span class="prix">
                                        <?php if ($article['enchère_max']): ?>
                                            Enchère max :
                                            <?= number_format($article['enchère_max'], 2) ?> €
                                        <?php else: ?>
                                            Prix départ :
                                            <?= number_format($article['prix_depart'], 2) ?> €
                                        <?php endif; ?>
                                    </span>
                                </div>
                                <span class="badge badge-info">
                                    <?= $article['nb_encheres'] ?> enchère(s)
                                </span>
                            </div>

                            <!-- Formulaire d'enchère -->
                            <?php if ($vente['etat'] === 'en_cours' && session()->get('id_utilisateur')): ?>
                                <div class="enchere-section">
                                    <h3>💰 Enchérir</h3>
                                    <?php
                                    $minimum = max($article['prix_depart'], 0.20);
                                    if ($article['enchère_max']) {
                                        $minimum = $article['enchère_max'] + 0.10;
                                    }
                                    ?>
                                    <form action="<?= base_url('/encheres/encherir/' . $article['id_vente_article']) ?>"
                                        method="post">
                                        <?= csrf_field() ?>
                                        <div class="enchere-form">
                                            <div class="form-group">
                                                <label>Montant (min.
                                                    <?= number_format($minimum, 2) ?> €)
                                                </label>
                                                <input type="number" name="montant" class="form-control" step="0.10"
                                                    min="<?= $minimum ?>" value="<?= $minimum ?>" required>
                                            </div>
                                            <button type="submit" class="btn btn-success">Enchérir</button>
                                        </div>
                                    </form>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <div class="icon">📦</div>
                    <h3>Aucun article dans cette vente</h3>
                    <p>Les bénévoles n'ont pas encore sélectionné d'articles.</p>
                </div>
            <?php endif; ?>

            <!-- Sélection d'article (bénévole/secrétaire) -->
            <?php if (in_array(session()->get('role'), ['benevole', 'secretaire']) && $vente['etat'] !== 'cloturee'): ?>
                <div class="card mt-3" style="padding: 2rem;">
                    <h3 style="margin-bottom: 1rem;">➕ Ajouter un article à cette vente</h3>
                    <form action="<?= base_url('/articles/selectionner/' . $vente['id_vente']) ?>" method="post">
                        <?= csrf_field() ?>
                        <div class="form-group">
                            <label>Article</label>
                            <select name="id_article" class="form-control" required>
                                <option value="">-- Sélectionner un article --</option>
                                <?php
                                $articleModel = new \App\Models\ArticleModel();
                                $articlesDisponibles = $articleModel->getArticlesDisponibles();
                                foreach ($articlesDisponibles as $a):
                                    ?>
                                    <option value="<?= $a['id_article'] ?>">
                                        <?= esc($a['libelle']) ?> (
                                        <?= esc($a['taille'] ?? 'N/A') ?>) -
                                        <?= number_format($a['prix_origine'], 2) ?> €
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Prix de départ (min. 0.20 €)</label>
                            <input type="number" name="prix_depart" class="form-control" step="0.10" min="0.20" value="0.20"
                                required>
                        </div>
                        <button type="submit" class="btn btn-primary">Ajouter l'article</button>
                    </form>
                </div>
            <?php endif; ?>
        </div>

        <!-- Sidebar -->
        <div class="detail-sidebar">
            <!-- Informations -->
            <div class="card">
                <h3 style="margin-bottom: 1rem;">📋 Informations</h3>
                <div class="card-body">
                    <p><strong>Début :</strong>
                        <?= date('d/m/Y à H:i', strtotime($vente['date_debut'])) ?>
                    </p>
                    <p><strong>Fin :</strong>
                        <?= date('d/m/Y à H:i', strtotime($vente['date_fin'])) ?>
                    </p>
                    <p><strong>Articles :</strong>
                        <?= count($articles) ?>
                    </p>

                    <?php if ($vente['etat'] === 'en_cours'): ?>
                        <?php
                        $fin = new DateTime($vente['date_fin']);
                        $now = new DateTime();
                        $diff = $now->diff($fin);
                        ?>
                        <div class="countdown mt-2">
                            ⏱️
                            <?php if ($diff->invert): ?>
                                Terminé
                            <?php else: ?>
                                <?= $diff->d ?>j
                                <?= $diff->h ?>h
                                <?= $diff->i ?>min restantes
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Inscription -->
            <?php if (session()->get('id_utilisateur') && $vente['etat'] !== 'cloturee'): ?>
                <div class="card">
                    <h3 style="margin-bottom: 1rem;">📝 Inscription</h3>
                    <?php if ($estInscrit): ?>
                        <p class="badge badge-success">✅ Vous êtes inscrit</p>
                    <?php else: ?>
                        <form action="<?= base_url('/ventes/inscrire/' . $vente['id_vente']) ?>" method="post">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-primary btn-block">S'inscrire à cette vente</button>
                        </form>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <!-- Inscrits -->
            <?php if (session()->get('role') === 'secretaire' && !empty($inscrits)): ?>
                <div class="card">
                    <h3 style="margin-bottom: 1rem;">👥 Inscrits (
                        <?= count($inscrits) ?>)
                    </h3>
                    <div class="card-body">
                        <?php foreach ($inscrits as $inscrit): ?>
                            <p>
                                <?= esc($inscrit['prenom'] . ' ' . $inscrit['nom']) ?>
                            </p>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>