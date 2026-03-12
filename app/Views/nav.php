<!-- Barre de navigation commune -->
<nav>
    <?= anchor('Enchere/index', 'Accueil'); ?>
    <?= anchor('Enchere/listeVentes', 'Ventes'); ?>

    <?php if (session()->get('estConnecte')): ?>
        <?php if (in_array(session()->get('role'), ['benevole', 'secretaire'])): ?>
            <?= anchor('Enchere/listeArticles', 'Articles'); ?>
        <?php endif; ?>

        <?php if (session()->get('role') === 'secretaire'): ?>
            <?= anchor('Enchere/dashboard', 'Dashboard'); ?>
        <?php endif; ?>

        <?php if (session()->get('role') === 'habitant'): ?>
            <?= anchor('Enchere/historiqueEncheres', 'Mes enchères'); ?>
            <?= anchor('Enchere/mesAchats', 'Mes achats'); ?>
        <?php endif; ?>

        <span class="nav-right">
            <?= anchor('Enchere/profil', 'Profil (' . session()->get('prenom') . ')'); ?>
            <?= anchor('Enchere/deconnexion', 'Déconnexion'); ?>
        </span>
    <?php else: ?>
        <span class="nav-right">
            <?= anchor('Enchere/connexion', 'Connexion'); ?>
            <?= anchor('Enchere/inscription', 'Inscription'); ?>
        </span>
    <?php endif; ?>
</nav>