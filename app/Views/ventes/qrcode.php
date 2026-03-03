<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="container">
    <div class="qrcode-container">
        <h1>📱 QR Code</h1>
        <h2>
            <?= esc($vente['titre']) ?>
        </h2>
        <p class="mt-1" style="color: var(--text-secondary);">Scannez ce QR code pour accéder directement à cette vente
        </p>

        <img src="<?= esc($qrCodeUrl) ?>" alt="QR Code de la vente" width="300" height="300">

        <p class="mt-2">
            <a href="<?= esc($venteUrl) ?>" class="btn btn-primary">🔗 Lien direct vers la vente</a>
        </p>
        <p class="mt-1">
            <a href="<?= base_url('/ventes/' . $vente['id_vente']) ?>" class="btn btn-secondary">← Retour à la vente</a>
        </p>
    </div>
</div>
<?= $this->endSection() ?>