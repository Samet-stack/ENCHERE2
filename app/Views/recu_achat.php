<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reçu d'achat - EnchèreAPorter</title>
    <!-- REÇU IMPRIMABLE : utiliser Ctrl+P ou le bouton pour imprimer / sauvegarder en PDF -->
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; padding: 20px; margin: 0; }
        .recu { max-width: 650px; margin: 0 auto; background: white; border: 1px solid #ddd; border-radius: 8px; padding: 30px; }
        .recu h1 { text-align: center; color: #2c3e50; }
        .recu h3 { color: #2c3e50; border-bottom: 1px solid #eee; padding-bottom: 5px; }
        .info { display: flex; justify-content: space-between; margin: 15px 0; }
        .info div { flex: 1; }
        .info p { margin: 3px 0; font-size: 14px; }
        .detail { background: #ecf0f1; border-radius: 8px; padding: 15px; margin: 15px 0; }
        table { width: 100%; border-collapse: collapse; }
        table th, table td { padding: 8px; text-align: left; border-bottom: 1px solid #bdc3c7; }
        .total { text-align: right; font-size: 22px; color: #27ae60; font-weight: bold; margin: 15px 0; }
        .footer { text-align: center; color: #999; font-size: 11px; border-top: 1px solid #eee; padding-top: 10px; margin-top: 20px; }
        .btn { display: inline-block; padding: 8px 16px; color: white; text-decoration: none; border-radius: 4px; border: none; cursor: pointer; margin: 5px; font-size: 14px; }
        .btn-print { background: #3498db; }
        .btn-back { background: #95a5a6; }
        .actions { text-align: center; margin-bottom: 15px; }

        /* Styles d'impression : masquer les boutons */
        @media print {
            body { background: white; padding: 0; }
            .recu { border: none; padding: 0; }
            .actions { display: none !important; }
        }
    </style>
</head>
<body>
    <!-- Boutons (masqués à l'impression) -->
    <div class="actions">
        <button class="btn btn-print" onclick="window.print();">🖨️ Imprimer / Enregistrer en PDF</button>
        <a href="<?= base_url('Enchere/mesAchats'); ?>" class="btn btn-back">← Retour</a>
    </div>

    <div class="recu">
        <h1>🧾 Reçu d'achat</h1>
        <p style="text-align:center;color:#7f8c8d;">EnchèreAPorter — Ville de Getcet</p>
        <p style="text-align:center;">Reçu N° <?= str_pad($achat->id_achat, 6, '0', STR_PAD_LEFT); ?></p>

        <!-- Acheteur + Vente -->
        <div class="info">
            <div>
                <h3>Acheteur</h3>
                <p><strong><?= $acheteur->prenom . ' ' . $acheteur->nom; ?></strong></p>
                <p><?= $acheteur->email; ?></p>
                <?php if (!empty($acheteur->telephone)): ?><p><?= $acheteur->telephone; ?></p><?php
endif; ?>
            </div>
            <div style="text-align: right;">
                <h3>Vente</h3>
                <p><strong><?= $achat->vente_titre; ?></strong></p>
                <p>Du <?= date('d/m/Y H:i', strtotime($achat->vente_date_debut)); ?></p>
                <p>Au <?= date('d/m/Y H:i', strtotime($achat->vente_date_fin)); ?></p>
            </div>
        </div>

        <!-- Article acheté -->
        <div class="detail">
            <table>
                <tr><th>Article</th><th>Taille</th><th>État</th><th>Prix origine</th><th>Montant final</th></tr>
                <tr>
                    <td><strong><?= $achat->article_libelle; ?></strong></td>
                    <td><?= $achat->taille ?? '-'; ?></td>
                    <td><?= $achat->article_etat ?? '-'; ?></td>
                    <td><?= number_format($achat->prix_origine, 2); ?> €</td>
                    <td><strong><?= number_format($achat->montant_final, 2); ?> €</strong></td>
                </tr>
            </table>
        </div>

        <div class="total">Total : <?= number_format($achat->montant_final, 2); ?> €</div>

        <p style="text-align:center;">
            <?= $achat->confirme ? '<span style="color:#27ae60;">✅ Confirmé</span>' : '<span style="color:#e67e22;">⏳ En attente</span>'; ?>
        </p>

        <div class="footer">
            <p>Reçu généré automatiquement — &copy; <?= date('Y'); ?> EnchèreAPorter</p>
        </div>
    </div>
</body>
</html>
