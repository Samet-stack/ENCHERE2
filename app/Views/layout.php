<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="EnchèreAPorter - Plateforme d'enchères de vêtements d'occasion pour la ville de Getcet">
    <title>
        <?= esc($title ?? 'EnchèreAPorter') ?>
    </title>
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-container">
            <a href="<?= base_url('/') ?>" class="nav-brand">Enchère<span>APorter</span></a>

            <button class="nav-toggle"
                onclick="document.querySelector('.nav-links').classList.toggle('active')">☰</button>

            <ul class="nav-links">
                <li><a href="<?= base_url('/') ?>">Accueil</a></li>
                <li><a href="<?= base_url('/ventes') ?>">Ventes</a></li>

                <?php if (session()->get('est_connecte')): ?>
                    <li><a href="<?= base_url('/encheres/historique') ?>">Mes enchères</a></li>
                    <li><a href="<?= base_url('/achats') ?>">Mes achats</a></li>

                    <?php if (session()->get('role') === 'secretaire'): ?>
                        <li><a href="<?= base_url('/dashboard') ?>">Dashboard</a></li>
                        <li><a href="<?= base_url('/ventes/creer') ?>">+ Vente</a></li>
                    <?php endif; ?>

                    <?php if (in_array(session()->get('role'), ['benevole', 'secretaire'])): ?>
                        <li><a href="<?= base_url('/articles') ?>">Articles</a></li>
                    <?php endif; ?>

                    <li><a href="<?= base_url('/profil') ?>">Profil</a></li>
                    <li><a href="<?= base_url('/deconnexion') ?>" class="btn btn-sm btn-danger">Déconnexion</a></li>
                <?php else: ?>
                    <li><a href="<?= base_url('/connexion') ?>" class="btn btn-sm btn-secondary">Connexion</a></li>
                    <li><a href="<?= base_url('/inscription') ?>" class="btn btn-sm btn-primary">Inscription</a></li>
                <?php endif; ?>
            </ul>

            <?php if (session()->get('est_connecte')): ?>
                <div class="nav-user">
                    <div class="nav-user-info">
                        <div class="name">
                            <?= esc(session()->get('prenom') . ' ' . session()->get('nom')) ?>
                        </div>
                        <div class="role">
                            <?= esc(session()->get('role')) ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </nav>

    <!-- Flash Messages -->
    <div class="container">
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success">✅
                <?= esc(session()->getFlashdata('success')) ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger">❌
                <?= esc(session()->getFlashdata('error')) ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger">
                <div>
                    <?php foreach (session()->getFlashdata('errors') as $err): ?>
                        <div>❌
                            <?= esc($err) ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Main Content -->
    <?= $this->renderSection('content') ?>

    <!-- Footer -->
    <footer class="footer">
        <p>©
            <?= date('Y') ?> <strong>EnchèreAPorter</strong> — Ville de Getcet | Friperie « Fripouilles »
        </p>
        <p style="margin-top: 0.5rem; font-size: 0.8rem;">Clique vite sinon c'est ton voisin qui le porte !</p>
    </footer>

    <script>
        // Auto-hide alerts after 5 seconds
        document.querySelectorAll('.alert').forEach(alert => {
            setTimeout(() => {
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-10px)';
                setTimeout(() => alert.remove(), 300);
            }, 5000);
        });
    </script>
</body>

</html>